<template>
    <div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-4">
                        <h3 class="card-title">{{ title }}</h3>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-4 mt-2">إذا كانت الكميه بالمخزن أقل من</div>
                            <div class="col-2"><input v-model="count" @keyup="loadData" class="form-control" type="text"></div>
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
                        <th>السعر</th>
                        <th>الكميه</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows.data" :key="row.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.sale_price }}</td>
                        <td>{{ row.stock }}</td>
                        <td>

                            <a href="#" data-target="#add_detail" @click="newDetail(row)"><i
                                class="fa fa-plus blue"></i></a> /
                            <a href="#" @click="loadDetail(row)"><i
                                class="fa fa-eye blue"></i></a> /

                            <a href="#" @click="loadPrice(row)"><i
                                class="fa fa-money blue"></i></a> /

                            <a v-if="'update_products' in allPermissions" href="#" :data-target="'#' + modalTitle" @click="editModal(row)"><i
                                class="fa fa-edit blue"></i></a> / <a v-if="'delete_products' in allPermissions" href="#" @click="deleteData(row.id)"><i
                            class="fa fa-trash red"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <pagination :limit="5" :data="rows" @pagination-change-page="getResults"></pagination>

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
            routeName: 'product',
            title: 'منتجات على وشك النفاد',
            subtitle: 'إعداد',
            count: '',
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
            // if(this.$gate.isAdminOrAuthor()) {
            axios.get('api/' + this.routeName + '?lessThan=' + this.count).then(({data}) => (this.rows = data));
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

        //


    }
}
</script>
