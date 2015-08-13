<template is="dom-bind" id="binder">
<ossso-page page-title="<?= $config->getSiteName() ?>">
    <paper-item>系统设置</paper-item>
    <paper-material card>
        <paper-input label="站点名称" value="{{config.siteName::blur}}" required auto-validate></paper-input>
        <iron-ajax auto method="POST" url="/api/config.php" body="[[_(config.*)]]"
                   content-type="application/x-www-form-urlencoded"
                   on-response="response" on-error="error"></iron-ajax>
    </paper-material>
    <paper-item>教师管理</paper-item>
    <section>
        <iron-ajax id="teachersManager" method="POST" url="/api/teacher/list.php" last-response="{{teachersResponse}}"
                   on-response="response" on-error="error"></iron-ajax>
        <div>
            <ossso-upload-button action="/api/teacher/import.php" button-text="导入..."></ossso-upload-button>
            <paper-button raised on-click="teachersRefresh">载入</paper-button>
        </div>
        <paper-material card class="table-wrapper">
            <table>
                <thead><tr><th>ID</th><th class="grow">姓名</th></tr></thead>
                <tbody>
                <template is="dom-repeat" items="[[teachersResponse.teachers]]">
                    <tr hidden$="[[item.deleting]]">
                        <td>[[item.TeacherID]]</td><td>[[item.TeacherName]]</td>
                        <td>
                            <paper-icon-button icon="delete" on-click="teacherDelete"></paper-icon-button>
                            <paper-tooltip>删除</paper-tooltip>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </paper-material>
    </section>
    <paper-item>学生管理</paper-item>
    <section>
        <iron-ajax id="studentsManager" method="POST" url="/api/student/list.php" last-response="{{studentsResponse}}"
                   on-response="response" on-error="error"></iron-ajax>
        <div>
            <ossso-upload-button action="/api/student/import.php" button-text="导入..."></ossso-upload-button>
            <paper-button raised on-click="studentsRefresh">载入</paper-button>
        </div>
        <paper-material card class="table-wrapper">
            <table>
                <thead><tr><th>ID</th><th class="grow">姓名</th><th>性别</th></tr></thead>
                <tbody>
                <template is="dom-repeat" items="[[studentsResponse.students]]">
                    <tr hidden$="[[item.deleting]]">
                        <td>[[item.StudentID]]</td><td>[[item.StudentName]]</td><td>[[item.StudentGender]]</td>
                        <td>
                            <paper-icon-button icon="delete" on-click="studentDelete"></paper-icon-button>
                            <paper-tooltip>删除</paper-tooltip>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </paper-material>
    </section>
    <paper-item>帐户管理</paper-item>
    <ossso-change-password></ossso-change-password>
</ossso-page>
<paper-toast id="deleted" text="已删除。" on-visible-changed="doDelete" on-tap="deletedDismiss">
    <span role="button" tabindex="0" on-click="undoDelete">撤销</span>
</paper-toast>
</template>
<script>
    var binder = document.querySelector('#binder');
    binder.addEventListener('dom-change', function () {
        function makeRequest(action, body) {
            var request = document.createElement('iron-request');
            request.completes.then(binder.response.bind(binder)).catch(binder.error.bind(binder));
            request.send({
                url: '/api/' + action + '.php',
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body,
                async: true,
                handleAs: 'json'
            });
        }
        function setDeleting(path, item, value) {
            binder.set(path + '.' + binder.get(path).indexOf(item) + '.deleting', value);
        }
        function removeItem(path, item) {
            var array = binder.get(path);
            var index = array.indexOf(item);
            if (index >= 0) binder.splice(path, index, 1);
        }

        binder._ = function (info) {
            return info.base;
        };
        binder.config = {
            siteName: '<?= $config->getSiteName() ?>'
        };
        binder.response = function (e) {
            if (!e.detail.response.success) paperToastManager.toast('更新失败：' + e.detail.response.errorMessage);
        };
        binder.error = function (e) {
            paperToastManager.toast('更新失败，未知错误：' + e.detail.error);
        };
        binder.doDelete = function (e) {
            if (e.detail.value) return;
            if (binder.deleteTeacher) {
                makeRequest('teacher/delete', { TeacherID: binder.deleteTeacher.TeacherID });
                removeItem('teachersResponse.teachers', binder.deleteTeacher);
                binder.deleteTeacher = null;
            }
            if (binder.deleteStudent) {
                makeRequest('student/delete', { StudentID: binder.deleteStudent.StudentID });
                removeItem('studentsResponse.students', binder.deleteStudent);
                binder.deleteStudent = null;
            }
        };
        binder.undoDelete = function () {
            if (binder.deleteTeacher) {
                setDeleting('teachersResponse.teachers', binder.deleteTeacher, false);
                binder.deleteTeacher = null;
            }
            if (binder.deleteStudent) {
                setDeleting('studentsResponse.students', binder.deleteStudent, false);
                binder.deleteStudent = null;
            }
            binder.$.deleted.hide();
        };
        binder.deletedDismiss = function () {
            binder.$.deleted.hide();
        };

        binder.teachersRefresh = function () {
            binder.$.teachersManager.generateRequest();
        };
        binder.teacherDelete = function (e) {
            setDeleting('teachersResponse.teachers', binder.deleteTeacher = e.model.item, true);
            binder.$.deleted.show();
        };

        binder.studentsRefresh = function () {
            binder.$.studentsManager.generateRequest();
        };
        binder.studentDelete = function (e) {
            setDeleting('studentsResponse.students', binder.deleteStudent = e.model.item, true);
            binder.$.deleted.show();
        };
    });
</script>
