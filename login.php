<template is="dom-bind" id="binder">
<ossso-page page-title="<?= $config->getSiteName() ?>" login>
    <paper-item>登录</paper-item>
    <paper-material card class="block-center" style="max-width: 400px;">
        <ossso-form>
            <paper-input id="uidField" label="用户 ID" value="{{uid}}" required></paper-input>
            <paper-input id="passwordField" label="密码" type="password" value="{{password}}" required></paper-input>
            <p><paper-checkbox checked="{{remember}}">7 天内自动登录</paper-checkbox></p>
            <div class="text-center">
                <paper-button default raised class="colored" on-click="login" disabled="[[loading]]">登录</paper-button>
            </div>
            <iron-ajax id="tester" method="POST" url="/api/login.php" loading="{{loading}}"
                       on-response="handleResponse" on-error="handleError"></iron-ajax>
        </ossso-form>
    </paper-material>
</ossso-page>
</template>
<script>
var binder = document.querySelector('#binder');
binder.addEventListener('dom-change', function () {
    binder.remember = true;
    binder.login = function () {
        if (this.loading || !(binder.$.uidField.validate() & binder.$.passwordField.validate())) return;
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
