<template>
    <div>

        <div class="row mt-3 mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <input type="text" class="form-control text-center" v-model="form.id" disabled>
                            </div>
                            <div class="col-6">
                                <button v-if="'create_orders' in allPermissions" class="btn btn-primary"
                                        :disabled="form.id != ''" @click="createData()"><i
                                    class="fa fa-plus"></i></button>
                                <button class="btn btn-success" :disabled="form.id == ''"
                                        @click="editMode == true ? saveOrder('edit') : saveOrder('new')"><i
                                    class="fa fa-save"></i></button>
                                <button v-if="'update_orders' in allPermissions" class="btn btn-info"
                                        @click="loadData()" data-toggle="modal"
                                        :disabled="form.id != ''"
                                        data-target="#edit-order"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" :disabled="form.id == ''" @click="cancelOrder()"><i
                                    class="fa fa-close"></i></button>
                                <!-- Modal -->
                                <div class="modal fade" id="edit-order" tabindex="-1"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <input type="text" v-model="orderSearch"
                                                       name="categorySearch" id="searchOrder"
                                                       @keyup="searchResults()" class="form-control"
                                                       placeholder="رقم الفاتوره">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="accordion" id="accordionExample">
                                                    <div class="card" v-for="(row, index) in rows.data">
                                                        <div class="card-header" id="headingOne">
                                                            <div style="cursor: pointer" @click="getOrder(row)"
                                                                 class="row" data-toggle="collapse"
                                                                 :data-target="'#collapseOne' + row.id"
                                                                 aria-expanded="true"
                                                                 :aria-controls="'collapseOne' + row.id">
                                                                <div class="col-3">{{ row.id }}</div>
                                                                <div class="col-9">{{ row.created_at }}</div>
                                                            </div>
                                                        </div>

                                                        <div :id="'collapseOne' + row.id" class="collapse"
                                                             aria-labelledby="headingOne"
                                                             data-parent="#accordionExample">
                                                            <div class="card-body">
                                                                <table style="cursor: pointer"
                                                                       class="table table-striped table-hover">
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <!--                                                                        <th scope="col">الباركود</th>-->
                                                                        <th scope="col">إسم المنتج</th>
                                                                        <th scope="col">الكميه</th>
                                                                        <th scope="col" style="width: 15% !important;">
                                                                            التخفيض
                                                                        </th>
                                                                        <th scope="col">السعر</th>
                                                                    </tr>
                                                                    <tr v-for="(detail, index) in orderDetails"
                                                                        @click="editModal(orderDetails)">
                                                                        <td>{{ index }}</td>
                                                                        <!--                                                                        <td>{{-->
                                                                        <!--                                                                                products[detail.product_id].barcode-->
                                                                        <!--                                                                            }}-->
                                                                        <!--                                                                        </td>-->
                                                                        <td>{{ detail.name }}</td>
                                                                        <td>{{ detail.quantity }}</td>
                                                                        <td>{{ detail.discount }}</td>
                                                                        <td>{{ detail.sale_price }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="modal-footer">
                                                <pagination :limit="2" :data="rows"
                                                            @pagination-change-page="getResults"></pagination>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-right">
                                <button v-if="'delete_orders' in allPermissions" class="btn btn-danger"
                                        :disabled="form.id == ''" @click="deleteData(form.id)"><i
                                    class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="category" class="col-sm-4 col-form-label">القسم</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" :data-category="data_category" v-model="categoryName"
                                                   id="category"
                                                   class="form-control" readonly
                                                   placeholder="">

                                            <div class="input-group-prepend">
                                                <button class="btn btn-sm btn-primary" type="button" data-toggle="modal"
                                                        :disabled="form.id == ''" data-target="#category-search"><i
                                                    class="fa fa-search"></i></button>
                                                <div class="modal fade" id="category-search" tabindex="-1"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <input type="text" v-model="categorySearch"
                                                                       name="categorySearch" id="searchCategory"
                                                                       @keyup="searchCategory()" class="form-control"
                                                                       placeholder="إسم القسم">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">


                                                                <table class="table table-striped table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">إسم القسم</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td @click="putCategoryId('all')">كل الأقسام
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-for="(category, index) in categories.data"
                                                                        @click="putCategoryId(category)">
                                                                        <td>{{
                                                                                category.name
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>


                                                            </div>

                                                            <div class="modal-footer">
                                                                <pagination :limit="5" :data="categories"
                                                                            @pagination-change-page="getCategoryResults"></pagination>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="username" class="col-sm-4 col-form-label">المستخدم</label>
                                    <div class="col-sm-8">
                                        <input type="text" :value="auth_name" readonly class="form-control"
                                               id="username">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="category" class="col-sm-4 col-form-label">العميل</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" v-model="clientName"
                                                   id="client" :data-client="data_client"
                                                   class="form-control" readonly
                                                   placeholder="">

                                            <div class="input-group-prepend">
                                                <button class="btn btn-sm btn-primary" type="button" data-toggle="modal"
                                                        :disabled="form.id == ''" data-target="#client-search"><i
                                                    class="fa fa-search"></i></button>
                                                <div class="modal fade" id="client-search" tabindex="-1"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-center">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <input type="text" v-model="clientSearch"
                                                                       name="clientSearch" id="searchClient"
                                                                       @keyup="searchClient()" class="form-control"
                                                                       placeholder="الإسم">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">


                                                                <table class="table table-striped table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">إسم العميل</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td @click="putClientId('all')">عشوائي</td>
                                                                    </tr>
                                                                    <tr v-for="(client, index) in clients.data"
                                                                        @click="putClientId(client)">
                                                                        <td>{{
                                                                                client.name
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>


                                                            </div>

                                                            <div class="modal-footer">
                                                                <pagination :limit="1" :data="clients"
                                                                            @pagination-change-page="getClientResults"></pagination>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="barcode" class="col-sm-4 col-form-label">الباركود</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text" id="barcode" readonly v-model="productName"
                                                   class="form-control"
                                                   autofocus
                                                   placeholder="الباركود أو الإسم">

                                            <div class="input-group-prepend">
                                                <button class="btn btn-sm btn-primary" type="button" data-toggle="modal"
                                                        :disabled="form.id == ''" data-target="#barcode-search"><i
                                                    class="fa fa-search"></i></button>
                                                <div class="modal fade" id="barcode-search" tabindex="-1"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <input type="text" v-model="productSearch"
                                                                       name="productSearch"
                                                                       id="searchProduct" @keyup="searchProduct()"
                                                                       class="form-control"
                                                                       placeholder="الباركود أو إسم المنتج">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">


                                                                <table class="table table-striped table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">إسم المنتج</th>
                                                                        <th scope="col">التخفيض</th>
                                                                        <th scope="col">الكميه</th>
                                                                        <th scope="col">السعر</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr v-for="(product, index) in products.data"
                                                                        @click="chooseProduct(product)">
                                                                        <td>{{ product.name }}</td>
                                                                        <td>{{ product.discount }}</td>
                                                                        <td>{{ product.stock }}</td>
                                                                        <td>{{ formatPrice(product.sale_price) }}</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>


                                                            </div>

                                                            <div class="modal-footer">
                                                                <pagination :limit="2" :data="products"
                                                                            @pagination-change-page="getProductResults"></pagination>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="quantity" class="col-sm-4 col-form-label">الكميه</label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" :max="maxQuantity" v-model="quantity"
                                               class="form-control" id="quantity">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">التخفيض</label>
                                    <div class="col-sm-4">
                                        <input type="number" min="1" v-model="discount"
                                               :placeholder="currentProduct.discount"
                                               class="form-control" id="discount">
                                    </div>
                                    <div class="col-sm-4">
                                        <button :disabled="form.id == '' || currentProduct.stock <= 0"
                                                class="btn btn-primary"
                                                @click="productName != '' ? putOrderList(currentProduct) : ''"><span><i
                                            class="fa fa-shopping-cart"></i></span>
                                            <span></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">


                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">إسم المنتج</th>
                                        <th scope="col">الكميه</th>
                                        <th scope="col" style="width: 15% !important;">التخفيض</th>
                                        <th scope="col">سعر الوحده</th>
                                        <th scope="col">السعر المدفوع</th>
                                        <th scope="col">التحكم</th>
                                    </tr>
                                    </thead>
                                    <tbody id="orderList" v-if="form.id != ''">
                                    <tr v-for="(id, index) in ids">
                                        <!--                                <input type="hidden" name="id" v-model="detail_form.order[id].id">-->
                                        <input type="hidden" name="order_id" v-model="detail_form.id">

                                        <th scope="row">{{ index }}</th>
                                        <td>{{ detail_form.order[id].name }}</td>
                                        <td>{{ detail_form.order[id].quantity }}</td>
                                        <td>{{ detail_form.order[id].discount }}</td>
                                        <td>{{ detail_form.order[id].sale_price }}</td>
                                        <td>{{ formatPrice(detail_form.order[id].paid_price) }}</td>
                                        <td style="min-width: 90px !important;">
                                            <button class="btn btn-sm btn-danger" @click="deleteFromList(id)"><i
                                                class="fa fa-minus"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer" v-if="form.id != ''">
                                <div class="row">
                                    <div class="col-6">
                                        <table class="table">
                                            <tr>
                                                <td>الجمله</td>
                                                <td>{{ formatPrice(totalPrice) }}</td>
                                            </tr>
                                            <tr>
                                                <td>التخفيض</td>
                                                <td>{{ formatPrice(totalDiscount) }}</td>
                                            </tr>
                                            <tr>
                                                <td>الصافي</td>
                                                <td>{{ formatPrice(price) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!---------------------->

        <div id="">
            <div id="MyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                 aria-hidden="true">

                <div class="modal-dialog modal-lg">

                    <!-- Modal Content: begins -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        </div>
                        <!-- Modal Header -->

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="invoice-box" id="printThis">

                                <div class="information text-center">
                                    <h3>{{ setting.name }}</h3>
                                    <div>{{ setting.location }}</div>
                                    <div>{{ setting.telephones }}</div>
                                    <div>فاتوره رقم : {{ orderId }}</div>
                                </div>

                                <table id="myTable" class="table text-center" cellpadding="0" cellspacing="0">
                                    <thead>
                                    <tr class="heading">
                                        <td>الرقم</td>
                                        <td>إسم المنتج</td>
                                        <td>الكميه</td>
                                        <td>سعر بيع الوحده</td>
                                        <td>التخفيض</td>
                                        <td>الجمله</td>

                                    </tr>
                                    </thead>

                                    <tr class="item" v-for="(id, index) in ids">
                                        <th scope="row">{{ index }}</th>
                                        <td>{{ detail_form.order[id].name }}</td>
                                        <td>{{ detail_form.order[id].quantity }}</td>
                                        <td>{{ detail_form.order[id].sale_price }}</td>
                                        <td>{{ detail_form.order[id].discount }}</td>
                                        <td>{{ formatPrice(detail_form.order[id].paid_price) }}</td>
                                    </tr>

                                    <tr class="total heading">
                                        <td colspan="5">الجمله</td>

                                        <td>{{ formatPrice(totalPrice) }}</td>
                                    </tr>
                                    <tr class="total heading">
                                        <td colspan="5">التخفيض</td>

                                        <td>{{ formatPrice(totalDiscount) }}</td>
                                    </tr>
                                    <tr class="total heading">
                                        <td colspan="5">المدفوع</td>

                                        <td>{{ formatPrice(price) }}</td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="printReport('printThis')"><i
                                class="fa fa-print"></i></button>
                        </div>

                    </div>
                    <!-- Modal Content: ends -->

                </div>
            </div>
        </div>


    </div>
</template>

<script>

export default {
    data() {
        return {
            ids: {},
            temps: {},
            editMode: false,
            modalTitle: 'orders',
            routeName: 'order',
            title: 'المبيعات',
            subtitle: 'مستخدم',
            allPermissions: {},
            rows: {},
            row: [],
            setting: {},
            orderDetails: [],
            categories: {},
            products: {},
            productList: {},
            data_category: '',
            data_client: null,
            categoryName: '',
            clientName: '',
            orderId: '',
            categorySearch: '',
            orderSearch: '',
            clientSearch: '',
            productSearch: '',
            currentProduct: [],
            lastProduct: [],
            productName: '',
            quantity: 1,
            discount: 0,
            totalDiscount: 0,
            totalPrice: 0,
            price: 0,
            clients: {},
            maxQuantity: 0,
            form: new Form({
                id: '',
                client_id: 1,
                user_id: this.auth_id
            }),
            detail_form: new Form({
                id: '',
                user_id: this.auth_id,
                order_id: '',
                order: {}
            }),
        }
    },
    props: ['auth_id', 'auth_name', 'permissions'],
    methods: {
        printReport(name) {
            $('#' + name).printThis();
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        editProductList(id) {
            this.productName = this.detail_form.order[id].name;
            this.currentProduct = this.detail_form.order[id];
            this.quantity = this.detail_form.order[id].quantity;
            this.discount = this.detail_form.order[id].discount;
            this.$delete(this.ids, id);
            this.$delete(this.detail_form.order, id);
        },
        searchClient() {
            if (this.categorySearch != '') {

                axios.get('api/category?name=' + this.categorySearch).then(({data}) => (this.categories = data));
            } else if (this.categorySearch == '') {
                axios.get('api/category').then(({data}) => (this.categories = data));
            }
        },
        getOrder(row) {
            this.editMode = true;
            this.ids = {};
            this.totalPrice = 0;
            this.totalDiscount = 0;
            this.price = 0;
            this.form.reset();
            this.detail_form.reset();
            this.form.id = row.id;
            this.form.client_id = row.client_id;
            axios.get('api/order/' + row.id)
                .then(response => {
                    this.orderDetails = response.data;
                });

        },
        saveOrder(status) {
            $("#MyModal").modal('show');
            this.orderId = this.form.id;
            if (status == 'new') {
                this.detail_form
                    .post("api/order_Detail")
                    .then(() => {
                        // Fire.$emit("afterCreate");
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
            } else if (status == 'edit') {
                this.detail_form
                    .put("api/order/" + this.form.id)
                    .then(() => {
                        // Fire.$emit("afterCreate");
                        toast.fire({
                            icon: "success",
                            title: "تم التعديل بنجاح"
                        });
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        toast.fire({
                            icon: "error",
                            title: "لم يتم التعديل"
                        });
                    });
            }
            this.form.reset();

        },

        searchResults() {
            if (this.orderSearch != '') {
                axios.get('api/order?id=' + this.orderSearch)
                    .then(response => {
                        this.rows = response;
                    });
            } else if (this.orderSearch == '') {
                axios.get('api/order').then(({data}) => (this.rows = data));
            }
        },

        searchCategory() {
            if (this.categorySearch != '') {
                axios.get('api/category?name=' + this.categorySearch).then(({data}) => (this.categories = data));
            } else if (this.categorySearch == '') {
                axios.get('api/category').then(({data}) => (this.categories = data));
            }
        },

        searchProduct() {

            if (this.productSearch != '' || this.data_category != '') {
                axios.get('api/product?name=' + this.productSearch + '&category=' + this.data_category).then(({data}) => (this.products = data));
            } else if (this.productSearch == '' && this.data_category == '') {
                axios.get('api/product').then(({data}) => (this.products = data));
            }

        },

        putCategoryId(category) {
            $("#category-search").modal("hide");
            if (category === 'all') {
                this.categoryName = 'كل الأقسم';
                this.data_category = '';
                // axios.get('api/category?getProducts=all').then(({data}) => (this.products = data));
                axios.get('api/product').then(({data}) => (this.products = data));
            } else {
                this.categoryName = category.name;
                this.data_category = category.id;
                // axios.get('api/category/' + this.data_category).then(({data}) => (this.products = data));
                axios.get('api/product?category=' + this.data_category).then(({data}) => (this.products = data));
            }

        },

        putClientId(client) {
            $("#client-search").modal("hide");
            if (client == 'all') {
                this.clientName = 'عشوائي';
                this.data_client = 1;
            } else {
                this.clientName = client.name;
                this.data_client = client.id;
            }
            this.form.client_id = this.data_client;

        },

        chooseProduct(product) {
            $("#barcode-search").modal("hide");
            this.productName = product.name;
            this.currentProduct = product;
            this.maxQuantity = product.stock;
        },
        putOrderList(product) {
            this.ids[product.id] = product.id;
            if (product.length != 0) {
                this.detail_form.order_id = this.form.id;
                if (this.detail_form.order[product.id] == undefined) {
                    this.detail_form.order[product.id] = Object.assign({}, product);
                    this.detail_form.order[product.id].discount = parseInt(this.discount, 10);
                    this.detail_form.order[product.id].quantity = parseInt(this.quantity, 10);
                    this.detail_form.order[product.id].product_id = product.id;
                } else {
                    this.detail_form.order[product.id].discount += parseInt(this.discount, 10);
                    this.detail_form.order[product.id].quantity += parseInt(this.quantity, 10);
                }
                this.detail_form.order[product.id].paid_price = (this.detail_form.order[product.id].sale_price * this.detail_form.order[product.id].quantity) - this.detail_form.order[product.id].discount;
                this.totalDiscount += parseInt(this.discount, 10);
                this.totalPrice += parseInt(this.detail_form.order[product.id].sale_price, 10) * parseInt(this.quantity, 10);
                this.price = this.totalPrice - this.totalDiscount;
            }


            product = [];
            this.currentProduct = [];
            this.productName = '';

            this.quantity = 1;
            this.discount = 0;
        },
        deleteFromList(id) {
            this.$delete(this.ids, id);
            this.totalDiscount -= this.detail_form.order[id].discount;
            this.totalPrice -= this.detail_form.order[id].sale_price * this.detail_form.order[id].quantity;
            this.price = this.totalPrice - this.totalDiscount;
            this.$delete(this.detail_form.order, id);
        },
        getResults(page = 1) {
            axios.get('api/' + this.routeName + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        getCategoryResults(page = 1) {
            axios.get('api/category?page=' + page)
                .then(response => {
                    this.categories = response.data;
                });
        },
        getProductResults(page = 1) {
            axios.get('api/product?page=' + page)
                .then(response => {
                    this.products = response.data;
                });
        },
        getClientResults(page = 1) {
            axios.get('api/client?page=' + page)
                .then(response => {
                    this.clients = response.data;
                });
        },
        updateData() {
            this.$Progress.start();
            this.form.put('api/' + this.routeName + '/' + this.form.id).then(() => {

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
            this.detail_form.reset();
            this.ids = {};
            this.temps = {};
            this.productList = {};
            // $("#" + this.modalTitle).modal('show');
        },
        editModal(row) {
            this.ids = {};
            this.temps = {};

            this.editMode = true;
            this.detail_form.order = row;
            this.productList = row;
            this.detail_form.order_id = this.form.id;
            this.ids = Object.assign({}, Object.keys(row));
            for (let id in this.ids) {
                this.temps[this.ids[id]] = this.ids[id];
            }
            this.ids = this.temps;
            for (let id in this.ids) {
                // this.detail_form.order[id].name = this.products[id].name;
                // this.detail_form.order[id].barcode = this.products[id].barcode;
                this.totalPrice += this.detail_form.order[id].sale_price * this.detail_form.order[id].quantity;
                this.totalDiscount += this.detail_form.order[id].discount
            }
            this.price = this.totalPrice - this.totalDiscount;
            $("#edit-order").modal('hide');
            // this.form.fill(row);
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
                        this.ids = {};
                        this.form.reset();
                        this.detail_form.reset();
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
            this.cancelOrder();
            axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
        },
        createData() {
            this.editMode = false;
            this.form.reset();
            this.detail_form.reset();
            this.price = 0;
            this.totalPrice = 0;
            this.totalDiscount = 0;
            this.ids = {};
            this.productList = {};
            this.$Progress.start();
            $("#" + this.modalTitle).modal('hide');
            this.form
                .post("api/" + this.routeName)
                .then((response) => {
                    this.row = response.data;
                    this.form.fill(this.row);
                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ"
                    });
                });
        },
        cancelOrder() {
            this.form.reset();
            this.detail_form.reset();
            this.price = 0;
            this.totalPrice = 0;
            this.totalDiscount = 0;
            this.ids = {};
            this.temps = {};
            this.rows = {};

            this.productList = {};
        }
    },
    created() {

        for (let permission in this.permissions) {
            this.allPermissions[this.permissions[permission]] = this.permissions[permission];
        }

        axios.get('api/category').then(({data}) => (this.categories = data));
        axios.get('api/product').then(({data}) => (this.products = data));
        axios.get('api/client').then(({data}) => (this.clients = data));
        this.loadData();
        this.putCategoryId('all');
        this.putClientId('all');
        // $("#MyModal").modal('show');
        axios.get('api/setting').then(({data}) => (this.setting = data));

    }
}
</script>
