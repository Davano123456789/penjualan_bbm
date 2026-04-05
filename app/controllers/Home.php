<?php

class Home extends Controller {
    public function index()
    {
        $data['judul'] = 'Dashboard';
        $data['view'] = 'home/index';
        
        // Test Database Connection via Model
        $userModel = $this->model('User_model');
        $data['total_users'] = $userModel->getUserCount();
        
        $this->view('templates/layout', $data);
    }
}
