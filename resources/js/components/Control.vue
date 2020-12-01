<template>
    <div>

        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="card-title">{{ title }}</h3>
                            </div>
                            <div class="col-6">
                                <input type="text" placeholder="بحث ...." class="form-control"
                                       v-model="searchClient" @keyup="searchResults">
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr class="text-center">
                                <th>رقم الفاتوره</th>
                                <th>التاريخ</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in rows.data" :key="row.id">
                                <td>{{ row.id }}</td>
                                <td>{{ row.created_at }}</td>
                                <td>
                                    <a href="#" @click="showOrder(row.id)"><i class="fa fa-eye red"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="rows" @pagination-change-page="getResults"></pagination>

                    </div>
                </div>
            </div>

            <div class="col-7">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="card-title">{{ title }}</h3>
                            </div>
                            <div class="col-6">
                                <input type="text" placeholder="بحث ...." class="form-control"
                                       v-model="searchClient" @keyup="searchResults">
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="table_id" class="table table-bordered table-hover">
                            <thead>
                            <tr class="text-center">
                                <th>رقم الفاتوره</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in rows.data" :key="row.id">
                                <td>{{ row.id }}</td>
                                <td>
                                    <a href="#" @click="showOrder(row.id)"><i class="fa fa-eye red"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="rows" @pagination-change-page="getResults"></pagination>

                    </div>
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
            modalTitle: 'control',
            routeName: 'control',
            title: 'متابعة المستخدمين',
            subtitle: 'عميل',
            searchClient: '',
            allPermissions: {},
            rows: {},
            orders: {},
            form: new Form({
                id: '',
                name: '',
                telephone: ''
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
            // if(this.$gate.isAdminOrAuthor()) {
            axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            // }
        },
        showOrder(id) {
            axios.get('api/' + this.routeName + '?order=' + id).then(({data}) => (this.orders = data));
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

        // for (let permission in this.permissions) {
        //     this.allPermissions[this.permissions[permission]] = this.permissions[permission];
        // }

    }
}
</script>
