<?
require($_SERVER["DOCUMENT_ROOT"] . '/api/init.api');
require($_SERVER["DOCUMENT_ROOT"] . '/api/connect.api');
require($_SERVER["DOCUMENT_ROOT"] . '/api/check-user.api');
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>YTA – <? echo TITLE; ?></title>
  <link rel="apple-touch-icon" href="/system/templates/images/ico/apple-icon-120.png">
  <link rel="shortcut icon" type="image/x-icon" href="images/ico/favicon.ico">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
  rel="stylesheet">

  <!-- BEGIN VENDOR CSS-->
  <link rel="stylesheet" type="text/css" href="/system/template/css/vendors.min.css">
  <link rel="stylesheet" type="text/css" href="/system/template/vendors/css/forms/icheck/icheck.css">
  <link rel="stylesheet" type="text/css" href="/system/template/vendors/css/forms/icheck/custom.css">
  <link rel="stylesheet" type="text/css" href="/system/template/vendors/css/extensions/toastr.css">
  <!-- END VENDOR CSS-->
  <!-- BEGIN MODERN CSS-->
  <link rel="stylesheet" type="text/css" href="/system/template/css/app.min.css">
  <!-- END MODERN CSS-->
  <!-- BEGIN Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="/system/template/css/core/menu/menu-types/vertical-content-menu.min.css">
  <link rel="stylesheet" type="text/css" href="/system/template/css/core/colors/palette-gradient.min.css">
  <link rel="stylesheet" type="text/css" href="/system/template/css/pages/login-register.min.css">
  <link rel="stylesheet" type="text/css" href="/system/template/css/plugins/extensions/toastr.min.css">
  <link rel="stylesheet" type="text/css" href="/system/template/css/plugins/animate/animate.min.css">
  <!-- END Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="https://docs.handsontable.com/pro/bower_components/handsontable-pro/dist/handsontable.full.min.css">
  <style>
    #hot-display-license-info {
        display: none;
    }
  </style>
</head>
<? if ($_SERVER["REQUEST_URI"] == '/auth/') : ?>
<body class="vertical-layout vertical-overlay-menu 1-column  bg-cyan bg-lighten-2 menu-expanded blank-page blank-page"
data-open="click" data-menu="vertical-overlay-menu" data-col="1-column">
<? else : ?>
<body class="vertical-layout vertical-content-menu 1-column   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-content-menu" data-col="1-column">
<? endif; ?>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <!-- fixed-top-->
  <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-dark navbar-shadow navbar-brand-center">
    <div class="navbar-wrapper">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="error-404-with-navbar.html#"><i class="ft-menu font-large-1"></i></a></li>
          <li class="nav-item">
            <a class="navbar-brand" href="/" style="margin-left: 150px;">
              <img src="https://re-spond.ru/img/logo.svg" style="width: 43%; margin-top: -20px;"/><br>
              <small style="color: white; margin-top: -8px; margin-left: -10px; position: absolute;">YouTube <img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/YouTube_logo_%282013-2015%29.png" width="30px"/> Analyzer</small>
            </a>
          </li>
          <li class="nav-item d-md-none">
            <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
          </li>
        </ul>
      </div>
      <? if ($userStatus == 'allow') : ?>
      <div class="navbar-container">
        <div class="collapse navbar-collapse justify-content-end" id="navbar-mobile">
          <ul class="nav navbar-nav">
            <li class="dropdown dropdown-user nav-item">
              <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown" aria-expanded="false">
                <span class="mr-1">Привет,
                  <span class="user-name text-bold-700"><?= $userdata['user_login']; ?></span>
                </span>
                <span class="avatar avatar-online">
                  <img src="/system/template/images/portrait/small/avatar-s-3.png" alt="avatar"><i></i></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#"><i class="ft-settings"></i> Настройки</a>
                <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="ft-power"></i> Выйти</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <? endif; ?>
    </div>
  </nav>
  <div class="app-content content">
    <div class="content-wrapper">
        <? if (!strContain($_SERVER["REQUEST_URI"], '/list/')) : ?>
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12">
                <h1 class="content-header-title mb-3"><? echo TITLE; ?></h1>
            </div>
        </div>
        <?else:?>
        <div class="content-header row">
  <div class="content-header-left col-md-6 col-12">
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Управление списками</a>
          </li>
          <li class="breadcrumb-item active" id="listBreadcrumbs"></li>
        </ol>
      </div>
    </div>
    <h2 class="content-header-title mb-0" id="listName"></h2>
    <h5 class="text-bold-800">
        <text id="listLastUpdate"></text> | <text id="listChannelsCount"></text>
    </h5>
  </div>
  <div class="content-header-right col-md-6 col-12">
    <div class="media">
      <div class="media-body media-right text-right">
      </div>
    </div>
  </div>
  <div class="content-header-lead col-12 mt-2">
    <p class="lead"></p>
  </div>
</div>
<?endif;?>
        <div class="content-body">
