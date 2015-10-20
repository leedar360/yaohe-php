<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
    	<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=1300" />
        <link rel="icon" href="/static/images/favicon.ico?v=1" type="image/x-icon" />
        <title>登录</title>
        <link href=/Public/console.1433267292.css rel="stylesheet" type="text/css" />
		<script src=/Public/jquery-1.7.2.min.js></script>
        <script type="text/javascript">
		$(document).ready(function(){
            $('a.refresh-captcha').on('click', function(e) {
                e.preventDefault();
                var $captcha = $('img.captcha');
                var src = $captcha.attr('src').split('?')[0];
                $captcha.attr('src', '?c=Login&a=captcha' + '&' + new Date().getTime());});
		});
        </script>
	</head>
    <body class="modal-ready">
    	<div class="container">
        	<div class="page-home">
            	<div class="wrapper">
                	<div class="form-wrapper">
                    	<form class="form" action="?c=Login&a=auth" method="POST">
                            <fieldset>
                            	<legend>登录</legend>
                                <div class="item user">
                                	<input type="text" name="login_user" value="" placeholder="用户名" />
								</div>
                                <div class="item password">
                                	<input type="password" name="login_pass" value="" placeholder="密码" />
								</div>
                                <div class="item captcha-field">
                                	<div class="controls">
                                    	<input class="captcha" type="text" name="vcode" value="" placeholder="验证码" />
                                    </div>
                                </div>
                                <div class="item">
                                	<img class="captcha" src="?c=Login&a=captcha" />
                                    <a class="link refresh-captcha" href="#">刷新</a>
                                </div>
                                <div class="item">
                                	<input class="btn btn-primary" type="submit" value="登录" />
                                </div>
							</fieldset>
                            <br>
						</form>
                      </div>
                </div>
            </div>
        </div>
	</body>
</html>