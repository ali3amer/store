<template>
    <div>

        <!--        <div v-if="!$gate.isAdminOrAuthor()">-->
        <!--            <not-found></not-found>-->
        <!--        </div>-->
        <div v-if="'read_users' in allPermissions" class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4"><h3 class="card-title">{{ title }}</h3></div>
                            <div class="col-6"><input type="text" placeholder="بحث ...." class="form-control"
                                                      v-model="searchUser" @keyup="searchResults"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card-tools text-right">
                            <button v-if="'create_users' in allPermissions" class="btn btn-primary" data-toggle="modal"
                                    @click="newModal()"
                                    :data-target="'#' + modalTitle"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr class="text-center">
                        <th>الرقم</th>
                        <th>الاسم</th>
                        <th>الإيميل</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows.data" :key="row.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.email }}</td>
                        <td>
                            <a v-if="'update_users' in allPermissions" href="#" :data-target="'#' + modalTitle"
                               @click="editModal(row)"><i
                                class="fa fa-edit blue"></i></a> / <a v-if="'delete_users' in allPermissions" href="#"
                                                                      @click="deleteData(row.id)"><i
                            class="fa fa-trash red"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <pagination :data="rows" @pagination-change-page="getResults"></pagination>

            </div>
        </div>
        <!-- /.box -->

        <div class="modal fade" :id="modalTitle" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" v-show="editMode" id="addNewLabel1">
                                تعديل بيانات {{ subtitle }}</h4>
                            <h4 class="modal-title" style="display:inline-block" v-show="!editMode" id="addNewLabel">
                                إضافة {{ subtitle }} جديد</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="إسم المستخدم"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email" placeholder="البريد الإلكتروني"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="كلمة المرور"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a v-for="(model, index) in models" class="nav-item nav-link"
                                       :class="index == 0 ? 'active' : ''" :id="'nav-' + model + '-tab'"
                                       data-toggle="tab" :href="'#nav-' + model" role="tab"
                                       :aria-controls="'nav-' + model" aria-selected="true">{{ arabicModels[index]
                                        }}</a>
                                </div>
                            </nav>
                            <div class="tab-content container-fluid" id="nav-tabContent">
                                <div v-for="(model, index) in models" class="tab-pane fade"
                                     :class="index == 0 ? 'show active' : ''" :id="'nav-' + model" role="tabpanel"
                                     :aria-labelledby="'nav-' + model + '-tab'">
                                    <div class="d-inline-block ml-3 mt-3" v-for="(map, index) in maps">
                                        <label><input class="mr-2" type="checkbox" name="permissions[]"
                                                      v-model="form.permissions"
                                                      :checked="map + '_' + model in userPermissions"
                                                      :value="map + '_' + model ">{{ arabicMaps[index] }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="modal-footer">
                            <button type="submit" v-show="editMode" class="btn btn-success">تعديل</button>
                            <button type="submit" v-show="!editMode" class="btn btn-primary">إضافه</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            modalTitle: 'users',
            routeName: 'user',
            title: 'المستخدمين',
            subtitle: 'مستخدم',
            models: ['users', 'categories', 'products', 'clients', 'expenses', 'orders'],
            arabicModels: ['المستخدمين', 'الأقسام', 'لمنتجات', 'العملاء', 'المصروفات', 'الطلبات'],
            maps: ['create', 'read', 'update', 'delete'],
            arabicMaps: ['إنشاء', 'عرض', 'تعديل', 'حذف'],
            searchUser: '',
            rows: {},
            editUser: {},
            userPermissions: {},
            allPermissions: {},
            form: new Form({
                id: '',
                name: '',
                email: '',
                password: '',
                permissions: []
            })
        }
    },
    props: ['auth_id', 'permissions'],
    methods: {
        getResults(page = 1) {
            axios.get('api/' + this.routeName + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        searchResults() {
            if (this.searchUser != '') {
                axios.get('api/' + this.routeName + '?name=' + this.searchUser).then(({data}) => (this.rows = data));
            } else if (this.searchUser == '') {
                axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            }
        },
        updateData() {
            this.$Progress.start();
            this.form.put('api/' + this.routeName + '/' + this.form.id).then(() => {
                // Fire.$emit('afterCreate');

                $("#" + this.modalTitle).modal('hide');
                this.loadData();
                toast.fire({
                    icon: 'success',
                    title: 'تم التعديل بنجاح'
                });

                this.$Progress.finish();
            })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم التعديل"
                    });
                });
        },
        newModal() {
            this.editMode = false;
            this.form.reset();
            // $("#" + this.modalTitle).modal('show');
        },
        editModal(row) {
            this.editMode = true;
            this.form.reset();
            $("#" + this.modalTitle).modal('show');
            this.form.fill(row);
            this.form.permissions = [];
            axios.get('api/' + this.routeName + '/' + row.id + '/edit').then(({data}) => (this.form.permissions = data));

        },
        deleteData(id) {
            swal.fire({
                title: 'هل أنت متأكد أنك تريد الحذف',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'لا',
                confirmButtonText: 'نعم'
            }).then((result) => {
                this.$Progress.start();
                if (result.value) {
                    this.form.delete('api/' + this.routeName + '/' + id).then(() => {
                        this.loadData();
                        this.$Progress.finish();
                        toast.fire({
                            icon: 'success',
                            title: 'تم الحذف بنجاح'
                        });
                        // Fire.$emit('afterCreate');
                    }).catch(() => {
                        toast.fire({
                            icon: "error",
                            title: "لم يتم الحذف"
                        });
                    });

                }
            });
        },
        loadData() {
            // if(this.$gate.isAdminOrAuthor()) {
            axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            // }
        },
        createData() {
            this.$Progress.start();
            $("#" + this.modalTitle).modal('hide');
            this.form
                .post("api/" + this.routeName)
                .then(() => {
                    // Fire.$emit("afterCreate");
                    $("#" + this.modalTitle).modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.loadData();
                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        }
    },
    created() {

        this.loadData();
        for (let permission in this.permissions) {
            this.allPermissions[this.permissions[permission]] = this.permissions[permission];
        }


    }
}
</script>
