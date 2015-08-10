<template is="dom-bind" id="binder">
<ossso-page page-title="<?= $config->getSiteName() ?>">
    <paper-item>系统设置</paper-item>
    <paper-material card>
        <paper-input label="站点名称" value="{{config.siteName::blur}}" required auto-validate></paper-input>
        <iron-ajax auto method="POST" url="/api/config.php" body="[[_(config.*)]]"
                   content-type="application/x-www-form-urlencoded"
                   on-response="configResponse" on-error="configError"></iron-ajax>
    </paper-material>
    <paper-item>教师管理</paper-item>
    <section>
        <div>
            <ossso-upload-button action="/api/import-teachers.php" button-text="导入..."></ossso-upload-button>
            <paper-button>刷新</paper-button>
        </div>
    </section>
    <paper-item>学生管理</paper-item>
    <section>
    </section>
    <paper-item>帐户管理</paper-item>
    <paper-material card>
        <ossso-change-password></ossso-change-password>
    </paper-material>
</ossso-page>
</template>
<script>
    var binder = document.querySelector('#binder');
    binder.addEventListener('dom-change', function () {
        binder._ = function (info) {
            return info.base;
        };
        binder.config = {
            siteName: '<?= $config->getSiteName() ?>'
        };
        binder.configResponse = function (e) {
            if (!e.detail.response.success) paperToastManager.toast('设置更新失败：' + e.detail.response.errorMessage);
        };
        binder.configError = function (e) {
            paperToastManager.toast('设置更新失败，未知错误：' + e.detail.error);
        };
    });
</script>
