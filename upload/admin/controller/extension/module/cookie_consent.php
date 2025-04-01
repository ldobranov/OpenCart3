<?php
class ControllerExtensionModuleCookieConsent extends Controller {
    private $error = [];

    public function index() {
        $this->load->language('extension/module/cookie_consent');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('cookie_consent', $this->request->post);
            $this->model_setting_setting->editSetting('module_cookie_consent', ['module_cookie_consent_status' => $this->request->post['cookie_consent_status']]);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/cookie_consent', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_message'] = $this->language->get('entry_message');
        $data['entry_button'] = $this->language->get('entry_button');
        $data['entry_info_page'] = $this->language->get('entry_info_page');
        $data['entry_bg_color'] = $this->language->get('entry_bg_color');
        $data['entry_text_color'] = $this->language->get('entry_text_color');
        $data['entry_button_color'] = $this->language->get('entry_button_color');
        $data['entry_font_size'] = $this->language->get('entry_font_size');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/cookie_consent', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['action'] = $this->url->link('extension/module/cookie_consent', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $settings = [
            'cookie_consent_status',
            'cookie_consent_message',
            'cookie_consent_button',
            'cookie_consent_info_id',
            'cookie_consent_bg_color',
            'cookie_consent_text_color',
            'cookie_consent_button_color',
            'cookie_consent_font_size'
        ];

        foreach ($settings as $setting) {
            if (isset($this->request->post[$setting])) {
                $data[$setting] = $this->request->post[$setting];
            } else {
                $data[$setting] = $this->config->get($setting);
            }
        }

        $this->load->model('catalog/information');
        $data['informations'] = $this->model_catalog_information->getInformations();
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/cookie_consent', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/cookie_consent')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
