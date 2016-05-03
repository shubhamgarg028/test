<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chat extends MY_Controller {

    public $model = "agents_model";

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/aura
     * 	- or -  
     * 		http://example.com/index.php/aura/index
     * 	- or -
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/aura/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();

        $this->data['pageTitle'] = "Manage Buildings";
        $this->data['subPageTitle'] = '';
        if (USER_TYPE !== 'agent') {
            redirect('authentication/index');
        }
    }

    public function index($page = 0, $flag = '0') {

        $this->load->model('agent_user_chat/User_chat');
        $this->data['title'] = 'Dashboard | Chat';
        $this->data['active_users'] = $this->User_chat->get_active_user();
        $this->load->view('agent_panel/agent_user_chat/list', $this->data);
    }

    public function insert_text() {

        $this->load->model('agent_user_chat/User_chat');
        $user_id = $this->input->post('userid');
        $text = $this->input->post('text');
        $username = $this->input->post('username');
        $this->load->library('api_manager_v1_1');

        $json = array();
        if (strlen($text) <= 0) {
            $json['error'] = "error:";
        }

        if (!$json['error']) {
            $data = array('sender_id' => $this->session->userdata(agent_id),
                'sender_type' => "agent",
                'reciever_id' => $user_id,
                'reciever_type' => "user",
                'message' => $text,
                'sent_date' => date('Y-m-d H:i:s'),
                'timestamp' => strtotime(date('Y-m-d H:i:s')),
                'is_read' => 0,
                'sender_name' => $this->session->userdata(agent_name),
                'receiver_name' => $username
            );

            $notification_data = $this->User_chat->insert_text_agent($data);
//            if ($notification_data['device_tokens']) {
//                        $this->api_manager_v1_1->sendNotification($notification_data);
//                    }
            $json['success'] = "sucess";
        }

        echo json_encode($json);
    }

    public function getdata() {

        $this->load->model('agent_user_chat/User_chat');
        $json = array();

        $user_id = $this->input->post('userid');
        $user_name = $this->input->post('username');
        $user_image = $this->input->post('user_photo');


        if (isset($user_id) && $user_id != "") {
            $data = $this->User_chat->get_chat_data($user_id);

            $last_id = 0;
            $first_id = isset($data[0]['chat_id']) ? $data[0]['chat_id'] : 0;

            foreach ($data as $value) {
                if ($value['chat_id'] > $last_id) {
                    $last_id = $value['chat_id'];
                }

                if ($first_id > $value['chat_id']) {
                    $first_id = $value['chat_id'];
                }
            }
            $data = array_reverse($data);
            $html = '';
            $html .='<input type="hidden" id="first_id" value="' . $first_id . '"/>';
            $html .='<input type="hidden" id="lastid" value="' . $last_id . '"/>';
            $html .='<input type="hidden" id="user_id" value="' . $user_id . '"/>';
            if (isset($data) && !empty($data)) {
                foreach ($data as $value) {
                    if ($value['sender_type'] == 'user') {
                        $html.= '<div class="row chat-message clearfix" style="min-height: 0;">';
                        $html.= '<div class="col-xs-9">';
                        $html .= '<div class="chat-incoming-message">';
                        $html .= $value['message'];
                        $html .= '  </div>';
                        $html .= ' </div>';
                        $html .= '	<div class="col-xs-1 chat-incoming-atatus">';
                        $html .= '<a href="#"><img class="img-circle" height="38" width="38" src="' . base_url() . 'uploads/agents/' . trim($user_image) . '"></a><br>';
                        $html .= '<span class="chat-time">' . date("h:i", $value['timestamp']) . '</span>';
                        $html .= '</div>';
                        $html .= '</div>';
                    } else {
                        $html.= '<div class="row chat-area clearfix">';
                        $html.= '<div class="col-xs-2 col-sm-2 chat-area-atatus">';
                        $html .= '<a href="#"><img  class="img-circle" height="38" width="38" src="' . base_url() . 'assets/agent/img/circle-img3.png" alt="agent_pic" alt=""></a><br>';
                        $html .= '<span class="chat-time">' . date("h:i", $value['timestamp']) . '</span>';
                        $html .= '</div>';
                        $html .= '<div class="col-xs-10 col-sm-10 chat-area-message">';
                        $html .= $value['message'];
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    // $this->User_chat->update_is_read($value['chat_id']);
                }
            }

            $json['html'] = $html;
            $json['user_name'] = $user_name;
            echo json_encode($json);
        }
    }

    public function update_chat() {

        $this->load->model('agent_user_chat/User_chat');
        $json = array();

        $user_id = $this->input->post('userid');
        $last_id = $this->input->post('lastid');
        $user_name = $this->input->post('username');
        $user_image = $this->input->post('user_photo');


        if (isset($user_id) && $user_id != "") {
            $data = $this->User_chat->update_chat_data($user_id, $last_id);

            if (!empty($data)) {

                foreach ($data as $value) {
                    if ($value['chat_id'] > $last_id) {
                        $last_id = $value['chat_id'];
                    }
                }
            }

            $data = array_reverse($data);
            $html = '';
            if (!empty($data)) {
                foreach ($data as $value) {
                    if ($value['sender_type'] == 'user') {
                        $html.= '<div class="row chat-message clearfix" style="min-height: 0;">';
                        $html.= '<div class="col-xs-9">';
                        $html .= '<div class="chat-incoming-message">';
                        $html .= $value['message'];
                        $html .= '  </div>';
                        $html .= ' </div>';
                        $html .= '	<div class="col-xs-1 chat-incoming-atatus">';
                        $html .= '<a href="#"><img class="img-circle" height="38" width="38" src="' . base_url() . 'uploads/agents/' . trim($user_image) . '"></a><br>';
                        $html .= '<span class="chat-time">' . date("h:i", $value['timestamp']) . '</span>';
                        $html .= '</div>';
                        $html .= '</div>';
                    } else {
                        $html.= '<div class="row chat-area clearfix">';
                        $html.= '<div class="col-xs-2 col-sm-2 chat-area-atatus">';
                        $html .= '<a href="#"><img  class="img-circle" height="38" width="38" src="' . base_url() . 'assets/agent/img/circle-img3.png" alt="agent_pic" alt=""></a><br>';
                        $html .= '<span class="chat-time">' . date("h:i", $value['timestamp']) . '</span>';
                        $html .= '</div>';
                        $html .= '<div class="col-xs-10 col-sm-10 chat-area-message">';
                        $html .= $value['message'];
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    // $this->User_chat->update_is_read($value['chat_id']);
                }
            }


            $json['html'] = $html;
            $json['last_id'] = $last_id;

            echo json_encode($json);
        }
    }

    public function get_active_user() {
        $this->load->model('agent_user_chat/User_chat');
        $json = array();
        $data = $this->User_chat->get_active_user();
        $html = "";
        foreach ($data as $value) {
            $json[] = $value['id'];
            $html .= ' <li class="row clearfix" onclick="chats(\' ' . $value['id'] . ' \',\' ' . $value['name'] . ' \',\' ' . $value['profile_image'] . ' \' )" id="li_' . $value['id'] . '" >
                                    <a href="#" class="col-sm-2"><img class="img-circle" height="38" width="38" src="' . base_url() . 'uploads/agents/' . $value['profile_image'] . '" alt="Circle Image"></a>
                                    <a href="#" class="col-sm-7"><span>' . $value['name'] . ' </span></a>
                                </li>';
        }

        // $json['html'] = $html;
        echo json_encode($json);
    }

    public function get_previous_chat() {
        $this->load->model('agent_user_chat/User_chat');
        $json = array();

        $user_id = $this->input->post('userid');
        $first_id = $this->input->post('firstid');
        $user_name = $this->input->post('username');
        $user_image = $this->input->post('user_photo');


        if (isset($user_id) && $user_id != "") {
            $data = $this->User_chat->get_previous_chat($user_id, $first_id);

            if (empty($data)) {
                echo $json;
                exit;
            }

            foreach ($data as $value) {
                if ($value['chat_id'] < $first_id) {
                    $first_id = $value['chat_id'];
                }
            }
            $data = array_reverse($data);
            $html = '';

            foreach ($data as $value) {
                if ($value['sender_type'] == 'user') {
                    $html.= '<div class="row chat-message clearfix" style="min-height: 0;">';
                    $html.= '<div class="col-xs-9">';
                    $html .= '<div class="chat-incoming-message">';
                    $html .= $value['message'];
                    $html .= '  </div>';
                    $html .= ' </div>';
                    $html .= '	<div class="col-xs-1 chat-incoming-atatus">';
                    $html .= '<a href="#"><img class="img-circle" height="38" width="38" src="' . base_url() . 'uploads/agents/' . trim($user_image) . '"></a><br>';
                    $html .= '<span class="chat-time">' . date("h:i", $value['timestamp']) . '</span>';
                    $html .= '</div>';
                    $html .= '</div>';
                } else {
                    $html.= '<div class="row chat-area clearfix">';
                    $html.= '<div class="col-xs-2 col-sm-2 chat-area-atatus">';
                    $html .= '<a href="#"><img  class="img-circle" height="38" width="38" src="' . base_url() . 'assets/agent/img/circle-img3.png" alt="agent_pic" alt=""></a><br>';
                    $html .= '<span class="chat-time">' . date("h:i", $value['timestamp']) . '</span>';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-10 col-sm-10 chat-area-message">';
                    $html .= $value['message'];
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }



            $json['html'] = $html;
            $json['first_id'] = $first_id;

            echo json_encode($json);
        }
    }

}
