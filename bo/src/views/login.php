<?php

use inc\Raise;

$this->subTtitle = "Login";

?>


<div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <h1 class="">Sign In</h1>
                    <p class="">Log in to your account to continue.</p>
                    
                    <form class="text-left">
                        <div class="form">

                            <div id="username-field" class="field-wrapper input">
                                <label for="username"><?= strtoupper(Raise::t('login', 'username')); ?></label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input id="user" name="user" type="text" class="form-control" placeholder="<?= Raise::t('login', 'username'); ?>">
                            </div>

                            <div id="password-field" class="field-wrapper input mb-2">
                                <div class="d-flex justify-content-between">
                                    <label for="password"><?= strtoupper(Raise::t('login', 'pass')); ?></label>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <input id="pass" name="pass" type="password" class="form-control" placeholder="<?= Raise::t('login', 'pass'); ?>">
                                
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="button" class="btn btn-primary" id="login"><?= Raise::t('login', 'log'); ?></button>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>                    
            </div>
        </div>
    </div>
</div>


