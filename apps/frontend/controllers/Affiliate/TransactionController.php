<?php

namespace Stupycart\Frontend\Controllers\Affiliate;

class TransactionController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		if (!$this->affiliate->isLogged()) {
			$this->session->set('redirect', $this->url->link('affiliate/transaction', '', 'SSL'));
			
	  		$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
    	}		
		
		$this->language->load('affiliate/transaction');

		$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_transaction'),
			'href'      => $this->url->link('affiliate/transaction', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->model_affiliate_transaction = new \Stupycart\Common\Models\Affiliate\Transaction();

    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));
		
		$this->data['text_balance'] = $this->language->get('text_balance');
		$this->data['text_empty'] = $this->language->get('text_empty');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
				
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}		
		
		$this->data['transactions'] = array();
		
		$data = array(				  
			'sort'  => 't.date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$transaction_total = $this->model_affiliate_transaction->getTotalTransactions($data);
	
		$results = $this->model_affiliate_transaction->getTransactions($data);
 		
    	foreach ($results as $result) {
			$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}	

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('affiliate/transaction', 'page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['balance'] = $this->currency->format($this->model_affiliate_transaction->getBalance());
		
		$this->data['continue'] = $this->url->link('affiliate/account', '', 'SSL');

		$this->view->pick('affiliate/transaction');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);		
	} 		
}
?>