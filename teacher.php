<template is="dom-bind" id="binder">
    <ossso-page page-title="<?= $config->getSiteName() ?>">
        <paper-item>课程设置</paper-item>
        <ossso-course-list teacher="[[teacher]]"></ossso-course-list>
        <paper-item>学生评价</paper-item>
        <section>Coming soon...</section>
        <paper-item>帐户管理</paper-item>
        <section>
            <paper-material card>
                <paper-input label="姓名" value="{{teacher.TeacherName::blur}}" required auto-validate></paper-input>
                <iron-ajax auto method="POST" url="/api/teacher/info.php" body="[[_(teacher.TeacherName)]]"
                           content-type="application/x-www-form-urlencoded"
                           on-response="response" on-error="error"></iron-ajax>
            </paper-material>
            <ossso-change-password></ossso-change-password>
        </section>
    </ossso-page>
</template>
<script>
    var binder = document.querySelector('#binder');
    binder.addEventListener('dom-change', function () {
        binder._ = function (TeacherName) {
            return { TeacherName: TeacherName };
        };
        binder.teacher = {
            TeacherID: <?= json_encode($user['TeacherID']) ?>,
            TeacherName: <?= json_encode($user['TeacherName']) ?>
        };
        binder.response = function (e) {
            if (!e.detail.response.success) paperToastManager.toast('更新失败：' + e.detail.response.errorMessage);
        };
        binder.error = function (e) {
            paperToastManager.toast('更新失败，未知错误：' + e.detail.error);
        };
    });
</script>
