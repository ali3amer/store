<template>
    <div>

        <!--        <div v-if="!$gate.isAdminOrAuthor()">-->
        <!--            <not-found></not-found>-->
        <!--        </div>-->

        <div class="row">
            <div class="col-6">
                <div v-if="'read_products' in allPermissions" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col-5"><h3 class="card-title">{{ title }}</h3></div>
                                            <div class="col-7"><input type="text" placeholder="بحث ...." class="form-control"
                                                                      v-model="searchProduct" @keyup="searchResults"></div>
                                        </div>
                                    </div>
                                    <div class="col-4"><select id="category" class="form-control"
                                                               @change="searchResults">
                                        <option value="">إختر القسم ........</option>
                                        <option v-for="(category, index) in categories" :key="category.id"
                                                :value="category.id">{{ category.name }}
                                        </option>
                                    </select></div>
                                </div>


                            </div>
                            <div class="col-2">
                                <div class="card-tools text-right">
                                    <button v-if="'create_products' in allPermissions" class="btn btn-primary" data-toggle="modal" @click="newModal()"
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
                        <pagination :data="rows" @pagination-change-page="getResults"></pagination>

                    </div>
                </div>
            </div>

            <div class="col-6">


                <div class="card card-primary" v-if="Object.keys(details).length != 0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-10"><h3 class="card-title">{{ product_name }}</h3></div>
                            <div class="col-2">
                                <div class="card-tools text-right">
                                    <button class="btn btn-primary" data-toggle="modal" @click="newDetail({'id': product_id, 'name': product_name})"
                                            :data-target="'#add_detail'"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم</th>
                                <th>سعر شراء الوحده</th>
                                <th>سعر بيع الوحده</th>
                                <th>الكميه</th>
                                <th>التخفيض</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(detail, index) in details.data" :key="detail.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ detail.purchase_price }}</td>
                                <td>{{ detail.sale_price }}</td>
                                <td>{{ detail.stock }}</td>
                                <td>{{ detail.discount }}</td>
                                <td>
                                    <a href="#" data-target="#add_detail" @click="editDetails(detail)"><i
                                        class="fa fa-edit blue"></i></a> / <a href="#"
                                                                              @click="deleteDetail(detail.id)"><i
                                    class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="details" @pagination-change-page="getDetailResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->

                <div class="modal fade" id="add_detail" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">

                        <div class="modal-content">
                            <form @submit.prevent="editDetail ? updateDetail() : createDetail()">
                                <input type="hidden" name="user_id" v-model="detail_form.user_id">
                                <input type="hidden" name="product_id" v-model="detail_form.product_id">
                                <div class="modal-header">
                                    <h4 class="modal-title" style="display:inline-block" v-show="editDetail"
                                        id="addNewLabel4">
                                        تعديل بيانات</h4>
                                    <h4 class="modal-title" style="display:inline-block" v-show="!editDetail"
                                        id="addNewLabel3">
                                        إضافة كميه جديده</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" :value="product_name" class="form-control" readonly>
                                    </div>

                                    <div class="row">

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.stock" type="number" name="stock"
                                                       placeholder="الكميه"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('stock') }">
                                                <has-error :form="detail_form" field="stock"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.purchase_price" type="number"
                                                       name="purchase_price" placeholder="سعر الشراء"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('purchase_price') }">
                                                <has-error :form="detail_form" field="purchase_price"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.sale_price" type="number" name="sale_price"
                                                       placeholder="سعر البيع"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('sale_price') }">
                                                <has-error :form="detail_form" field="sale_price"></has-error>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <input v-model="detail_form.discount" type="number" name="discount"
                                                       placeholder="التخفيض"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': detail_form.errors.has('discount') }">
                                                <has-error :form="detail_form" field="discount"></has-error>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" v-show="editDetail" class="btn btn-success">تعديل</button>
                                    <button type="submit" v-show="!editDetail" class="btn btn-primary">إضافه</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- /.card -->

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

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input v-model="form.barcode" type="text" name="barcode" placeholder="باركود"
                                               class="form-control" id="barcode"
                                               :class="{ 'is-invalid': form.errors.has('barcode') }">
                                        <has-error :form="form" field="barcode"></has-error>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.category_id" name="category_id"
                                                :class="{ 'is-invalid': form.errors.has('category_id') }">
                                            <option value="">إختر القسم ........</option>
                                            <option v-for="(category, index) in categories" :key="category.id"
                                                    :value="category.id">{{ category.name }}
                                            </option>
                                        </select>
                                        <has-error :form="form" field="category_id"></has-error>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="إسم المنتج"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="row">
                                <div v-show="editMode" :class="editMode ? 'col-6' : '' " class="form-group">
                                    <label>سعر الشراء</label>
                                    <input v-model="form.purchase_price" v-show="editMode" type="text" name="purchase_price" placeholder="سعر الشراء"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('purchase_price') }">
                                    <has-error :form="form" field="purchase_price"></has-error>
                                </div>

                                <div :class="editMode ? 'col-6' : '' " class="form-group" v-show="editMode">
                                    <label>سعر البيع</label>
                                    <input v-model="form.sale_price"  type="text" name="sale_price" placeholder="سعر البيع"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('sale_price') }">
                                    <has-error :form="form" field="sale_price"></has-error>
                                </div>

                                <div v-show="editMode" class="form-group col-6"><label><span>الكميه المتبقيه: </span>{{ form.stock }}</label></div>
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


        <div class="modal fade" id="showPrice" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" id="addNewLabel2">
                                {{ product_name }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr class="text-center">
                                    <th>الرقم</th>
                                    <th>السعر القديم</th>
                                    <th>السعر الجديد</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(price, index) in prices.data" :key="price.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ price.old_price }}</td>
                                    <td>{{ price.new_price }}</td>
                                    <td>{{ price.created_at }}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <pagination :data="prices" @pagination-change-page="getPriceResults"></pagination>
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
            editDetail: false,
            modalTitle: 'products',
            routeName: 'product',
            title: 'المنتجات',
            subtitle: 'منتج',
            allPermissions: {},
            rows: {},
            prices: {},
            details: {},
            detail: {},
            categories: {},
            product_name: '',
            product_id: '',
            searchProduct: '',
            detail_title: this.product_name,
            form: new Form({
                id: '',
                barcode: '',
                name: '',
                category_id: '',
                purchase_price: '',
                sale_price: '',
                discount: '',
                stock: ''
            }),
            detail_form: new Form({
                id: '',
                product_id: this.product_id,
                purchase_price: '',
                sale_price: '',
                stock: '',
                discount: '',
                user_id: this.auth_id
            })
        }
    },
    props:['auth_id', 'permissions'],
    methods: {
        searchResults() {
            if (this.searchProduct != '' || $('#category').val() != null) {
                axios.get('api/' + this.routeName + '?name=' + this.searchProduct + '&category=' + $('#category').val()).then(({data}) => (this.rows = data));
            } else {
                axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            }
        },
        getDetailResults(page = 1) {
            axios.get('api/product/' + this.product_id + '?page=' + page)
                .then(response => {
                    this.details = response.data;
                });
        },
        newDetail(row) {
            this.editDetail = false;
            this.detail_form.reset();
            this.detail_form.product_id = row.id;
            this.product_name = row.name;
            $("#add_detail").modal('show');
        },
        createDetail() {
            this.$Progress.start();
            $("#add_detail").modal('hide');
            this.detail_form
                .post("api/product_Detail")
                .then(() => {
                    $("#add_detail").modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.loadDetail({'id': this.detail_form.product_id, 'name': this.product_name});
                    this.loadData();

                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        },
        editDetails(detail) {
            this.editDetail = true;
            this.detail_form.reset();
            $("#add_detail").modal('show');
            this.detail_form.fill(detail);
        },
        updateDetail() {
            this.$Progress.start();
            this.detail_form.put('api/product_Detail/' + this.detail_form.id).then(() => {
                // Fire.$emit('afterCreate');

                $("#add_detail").modal('hide');
                this.loadDetail({'id': this.detail_form.product_id, 'name': this.product_name});
                toast.fire({
                    icon: "success",
                    title: "تم الحفظ بنجاح"
                });

                this.$Progress.finish();
            })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        },
        deleteDetail(id) {
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
                    this.detail_form.delete('api/product_Detail/' + id).then(() => {
                        this.loadDetail({'id': this.product_id, 'name': this.product_name});
                        this.$Progress.finish();
                        toast.fire({
                            icon: "success",
                            title: "تم الحذف بنجاح"
                        });
                        // Fire.$emit('afterCreate');
                    }).catch(() => {
                        toast.fire({
                            icon: "error",
                            title: "لم الحذف الحفظ"
                        });
                    });

                }
            });
        },
        loadDetail(row) {
            this.detail = row;
            this.product_name = row.name;
            this.product_id = row.id;
            // this.detail_form.user_id = this.auth_id;
            axios.get('api/product/' + row.id).then(({data}) => (this.details = data));
        },
        getResults(page = 1) {
            axios.get('api/' + this.routeName + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        getPriceResults(page = 1) {
            axios.get('api/price?product_id=' + this.product_id + '&page=' + page)
                .then(response => {
                    this.prices = response.data;
                });
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
            $("#barcode").attr("autofocus", "autofocus");
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
        loadPrice(row) {
            $("#showPrice").modal('show');
            axios.get('api/price?product_id=' + row.id).then(({data}) => (this.prices = data));
            this.product_name = row.name;
            this.product_id = row.id;
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

        axios.get('api/category?category=all').then(({data}) => (this.categories = data));

        this.loadData();

        for (let permission in this.permissions) {
            this.allPermissions[this.permissions[permission]] = this.permissions[permission];
        }
    }
}
</script>
