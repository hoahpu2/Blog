<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class PostsController extends Controller {
    public $uses = array('Posts', 'Comments');

    public function initialize()
    {
        parent::initialize();
        // $this->layout = 'ajax'; 
        // $this->render(false);
    } 

    public $components = array('Paginator');

    public $paginate = array(
        'page' => 1,
        'limit' => 5,
        'order' => array('Posts.date_posted' => 'desc')
    );

    public function index(){
        $this->layout = 'ajax';
        $this->render(false);
        $this->paginate['page'] = $_GET['page'];
        $this->Paginator->settings = $this->paginate;

        // similar to findAll(), but fetches paged results
        $a_GetAllPosts = $this->Paginator->paginate('Posts');   

        foreach($a_GetAllPosts as $key => $post){
            $a_GetAllPosts[$key] = $post['Posts'];
        }

        $count = $this->Posts->find('count');
        
        $data['data'] = $a_GetAllPosts;
        $data['totalPage'] = ceil($count/$this->paginate['limit']);
        $data['currentPage'] = $_GET['page'];
        // xx($data);
        echo json_encode($data);
    }

    public function create(){
        $this->layout = 'ajax';
        $this->render(false);
        if($this->request->is('post')){
            // array com os dados
            $salvarPosts = array(
                'title' => $this->request->data['title'],
                'description' => $this->request->data['description'],
                'body' => $this->request->data['body'],
                'date_posted' => date('Y-m-d H:i:s'),
                'author' => $this->request->data['author'],
            );
            
            if(!empty($this->request->params['form']['file'])){
                $img = file_get_contents($this->request->params['form']['file']['tmp_name']);
                $salvarPosts['file'] = base64_encode($img);
            }

            if(!empty($this->request->data['id'])){
                $this->Posts->id = $this->request->data['id'];
            } else {
                $this->Posts->create();
            }
            // Insere os registros na tabela
            $this->Posts->save($salvarPosts);
        }
    }

    public function getDetail($id){
        $this->layout = 'ajax';
        $this->render(false);
        $Posts = $this->Posts->find('first',  array(
            // You add the condition(s)
            'conditions' => array('Posts.id' => $id),
        ));
        echo json_encode($Posts['Posts']);
    }

    public function createComment(){
        $this->layout = 'ajax';
        $this->render(false);
        if($this->request->is('post')){
            // array com os dados
            $salvarComment = array(
                'post_id' => $this->request->data['post_id'],
                'content' => $this->request->data['content'],
                'time_comment' => date('Y-m-d H:i:s'),
            );

            $this->Comments->create();
            // Insere os registros na tabela
            $this->Comments->save($salvarComment);
        }
    }

    public function getCommentByPost($id){
        $this->layout = 'ajax';
        $this->render(false);
        $a_GetAllComment = $this->Comments->find('all', array(
            'order' => array('Comments.time_comment' => 'desc'),
            'conditions' => array('Comments.post_id' => $id)
        ));

        foreach($a_GetAllComment as $key => $post){
            $a_GetAllComment[$key] = $post['Comments'];
        }

        echo json_encode($a_GetAllComment);
    }

    public function deletePost($id){
        echo $id;
        $this->layout = 'ajax';
        $this->render(false);
        $this->Comments->deleteAll([
            'Comments.post_id'=>$id,
        ],false);
        $this->Posts->id = $id;
        $this->Posts->delete();
    }
}
