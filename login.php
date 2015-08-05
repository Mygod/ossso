<template is="dom-bind" id="binder">
<ossso-page page-title="<?= $config->getSiteName() ?>" login>
    <paper-item>登录</paper-item>
    <section class="block-center" style="max-width: 400px;">
        <paper-input id="uidField" label="用户 ID" value="{{uid}}" required></paper-input>
        <paper-input id="passwordField" label="密码" type="password" value="{{password}}" required></paper-input>
        <p><paper-checkbox checked="{{remember}}">7 天内自动登录</paper-checkbox></p>
        <div class="text-center">
            <paper-button raised class="colored" on-tap="login" disabled="[[loading]]">登录</paper-button>
        </div>
    </section>
</ossso-page>
<iron-ajax id="tester" method="POST" url="/api/login.php" debounce-duration="1000" loading="{{loading}}"
           on-response="handleResponse" on-error="handleError"></iron-ajax>
</template>
<script>
var binder = document.querySelector('#binder');
binder.addEventListener('dom-change', function () {
    binder.remember = true;
    binder.login = function () {
        if (!(binder.$.uidField.validate() & binder.$.passwordField.validate())) return;
        var remember = binder.remember ? '; max-age=604800' : '';
        document.cookie = 'uid=' + encodeURIComponent(binder.uid) + remember;
        document.cookie = 'password=' + sha512(binder.password) + remember;
        binder.$.tester.generateRequest();
    };
    binder.handleResponse = function (e) {
        if (e.detail.response.success) location.reload();
        else paperToastManager.toast('登录失败：' + e.detail.response.errorMessage);
    };
    binder.handleError = function (e) {
        paperToastManager.toast('登录失败，未知错误：' + e.detail.error);
    };
});
</script>
