<template>
    <div>

        <!--        <div v-if="!$gate.isAdminOrAuthor()">-->
        <!--            <not-found></not-found>-->
        <!--        </div>-->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <div class="col-3"><h3 class="card-title">{{ title }}</h3></div>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <form @submit.prevent="createData()">
                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name" placeholder="إسم المحل"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.telephones" type="text" name="telephones" placeholder="أرقام الهواتف"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('telephones') }">
                        <has-error :form="form" field="telephones"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.location" type="text" name="location" placeholder="الموقع"
                               class="form-control" :class="{ 'is-invalid': form.errors.has('location') }">
                        <has-error :form="form" field="location"></has-error>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" @click="createData()" class="btn btn-primary">إضافه</button>
            </div>
        </div>
        <!-- /.box -->


    </div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            modalTitle: 'settings',
            routeName: 'setting',
            title: 'الإعدادات',
            subtitle: 'إعداد',
            searchClient: '',
            allPermissions: {},
            rows: {},
            form: new Form({
                id: '',
                name: '',
                telephones: '',
                location: ''
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
            if (this.searchClient != '') {
                axios.get('api/' + this.routeName + '?name=' + this.searchClient).then(({data}) => (this.rows = data));
            } else if (this.searchClient == '') {
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
            this.form.get('api/' + this.routeName).then(({data}) => {
                this.rows = data;
                this.form.reset();
                this.form.fill(this.rows);
            }).catch(() => {
                toast.fire({
                    icon: "error",
                    title: "حدث خطأ"
                });
            });
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
