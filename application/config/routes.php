<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'index/home';
$route['home'] = 'index/home';
$route['logout'] = 'index/logout';
$route['contact'] = 'index/contact';
$route['about'] = 'index/about';
$route['pricing'] = 'index/pricing';
$route['login'] = 'index/signin';
$route['reset-password'] = 'index/forgot_password';
$route['verify_code'] = 'index/verify_get';
$route['register'] = 'index/register';
$route['create_password'] = 'index/password_get';
$route['reset'] = 'index/reset';;
$route['logout'] = 'index/logout';
$route['test1'] = 'index/check_redirect';
$route['new_cv_parser/(:any)/(:any)/(:any)'] = 'index/new_cv_parser_post';

$route['verify'] = 'dashboard/verify';

$route['invoice'] = 'index/invoice';

$route['dashboard'] = 'dashboard/profile';
$route['kyc'] = 'dashboard/kyc';
$route['loan-application'] = 'dashboard/apply_loan';
$route['earnings'] = 'dashboard/earnings';
$route['application-status'] = 'dashboard/kyc_status';
$route['seller-application'] = 'dashboard/seller_application';
$route['referrals'] = 'dashboard/referrals';

$route['upgrade'] = 'dashboard/payment';
$route['donate'] = 'index/donation';
$route['payment_done'] = 'dashboard/payment_success';
$route['start_payment'] = 'dashboard/start_payment';
$route['start_donation'] = 'index/start_donation';
$route['set_new_password'] = 'index/set_new_password';
$route['seller'] = 'index/check_seller';


$route['form/seller_search'] = 'contact_form/seller_search';
$route['form/contact'] = 'contact_form/contact_form';
$route['form/register'] = 'contact_form/register';
$route['form/login'] = 'contact_form/login';
$route['form/kycform'] = 'contact_form/kyc_submit';
$route['form/reset-password'] = 'index/send_reset_password_mail';


//admim
$route['admin/index'] = 'admin/index';
$route['admin/dashboard'] = 'admin/dashboard';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
