<template is="dom-bind" id="binder">
    <ossso-page page-title="<?= $config->getSiteName() ?>">
        <paper-item>帐户管理</paper-item>
        <section>
            <paper-material card>
                <paper-input label="姓名" value="[[student.StudentName]]" readonly></paper-input>
                <paper-input label="性别" value="[[student.StudentGender]]" readonly></paper-input>
                <paper-textarea label="自我介绍" value="{{student.StudentIntroduction::blur}}"></paper-textarea>
                <iron-ajax auto method="POST" url="/api/student/info.php" body="[[_(student.StudentIntroduction)]]"
                           content-type="application/x-www-form-urlencoded"
                           on-response="response" on-error="error"></iron-ajax>
            </paper-material>
            <ossso-change-password></ossso-change-password>
        </section>
        <paper-item>选择课程</paper-item>
        <ossso-course-list student="[[student]]"></ossso-course-list>
        <paper-item>教师评价</paper-item>
        <section>Coming soon...</section>
    </ossso-page>
</template>
<script>
    var binder = document.querySelector('#binder');
    binder.addEventListener('dom-change', function () {
        binder._ = function (StudentIntroduction) {
            return { StudentIntroduction: StudentIntroduction };
        };
        binder.student = {
            StudentID: <?= json_encode($user['StudentID']) ?>,
            StudentName: <?= json_encode($user['StudentName']) ?>,
            StudentGender: <?= json_encode($user['StudentGender']) ?>,
            StudentIntroduction: <?= json_encode($user['StudentIntroduction']) ?>
        };
        binder.response = function (e) {
            if (!e.detail.response.success) paperToastManager.toast('更新失败：' + e.detail.response.errorMessage);
        };
        binder.error = function (e) {
            paperToastManager.toast('更新失败，未知错误：' + e.detail.error);
        };
    });
</script>
