<template>
    <div>

        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="category" class="col-sm-4 col-form-label">القسم</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" :data-category="data_category"
                                                           v-model="categoryName"
                                                           id="category"
                                                           class="form-control" readonly
                                                           placeholder="">

                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-sm btn-primary" type="button"
                                                                data-toggle="modal"
                                                                data-target="#category-search"><i
                                                            class="fa fa-search"></i></button>
                                                        <div class="modal fade" id="category-search" tabindex="-1"
                                                             aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <input type="text" v-model="categorySearch"
                                                                               name="categorySearch" id="searchCategory"
                                                                               @keyup="searchCategory()"
                                                                               class="form-control"
                                                                               placeholder="إسم القسم">
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal"
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
                                                                                <td @click="putCategoryId('all')">كل
                                                                                    الأقسام
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
                                                                        <pagination :data="categories"
                                                                                    @pagination-change-page="getResults"></pagination>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">مصروفات</label>
                                            <div class="col-sm-8">
                                                <input type="text" :value="formatPrice(expense)" readonly
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">هذا الشهر</label>
                                            <div class="col-sm-8">
                                                <input type="text" :value="formatPrice(month)" readonly
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">اليوم</label>
                                            <div class="col-sm-8">
                                                <input type="text" :value="formatPrice(todayPaid)" readonly
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="from" class="col-sm-3 col-form-label">من</label>
                                    <div class="col-sm-9">
                                        <input type="date" :disabled="from_to_form.category_id == ''"
                                               v-model="from_to_form.from" required class="form-control" id="from">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="to" class="col-sm-3 col-form-label">الى</label>
                                    <div class="col-sm-9">
                                        <input type="date" v-model="from_to_form.to" @change="createData('from_to')"
                                               :disabled="from_to_form.from == ''" required class="form-control"
                                               id="to">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary" :disabled="from_to_form.category_id == ''"
                                @click="createData('inventory_form')"><span><i
                            class="fa fa-calculator"></i></span>
                            <span> جرد</span></button>
                        <button class="btn btn-success" :disabled="from_to_form.category_id == ''"
                                @click="createData('today_form')"><span><i
                            class="fa fa-calendar"></i></span>
                            <span> تقرير اليوم</span></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">


                <div class="card mb-3" v-if="Object.keys(from_to).length != 0">

                    <div class="card-body">
                        <button class="btn btn btn-primary" @click="printReport('from_to')"><i class="fa fa-print"></i>
                        </button>

                        <div class="invoice-box" id="from_to">

                            <div class="information text-center">
                                <h3>{{ setting.name }}</h3>
                                <div>{{ setting.location }}</div>
                                <div>{{ setting.telephones }}</div>
                                <div><span>تقرير الفتره من : </span><span>{{ from_to_form.from }}</span><span>الى: </span><span>{{ from_to_form.to }}</span></div>
                            </div>

                            <div v-for="(row, index) in from_to">
                                <h4>{{ row.name }}</h4>

                                <table class="table text-center" cellpadding="0" cellspacing="0">
                                    <thead>
                                    <tr class="heading">
                                        <td>الرقم</td>
                                        <td>إسم المنتج</td>
                                        <td>سعر شراء الوحده</td>
                                        <td>الكميه</td>
                                        <td>التخفيض</td>
                                        <td>المدفوع</td>
                                        <td>التاريخ</td>
                                    </tr>
                                    </thead>

                                    <tr class="item" v-for="(item, index) in row.orders">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.product_name }}</td>
                                        <td>{{ formatPrice(item.sale_price) }}</td>
                                        <td>{{ item.quantity }}</td>
                                        <td>{{ item.discount }}</td>
                                        <td>{{ formatPrice(item.paid_price) }}</td>
                                        <td>{{ item.created_at }}</td>
                                    </tr>

                                    <tr class="total heading">
                                        <td colspan="5">الجــــــــــــلـــمه</td>
                                        <td>{{ formatPrice(from_to_sum[row.id]) }}</td>
                                        <td></td>
                                    </tr>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="card mb-3" v-if="Object.keys(rows).length != 0">
                    <div class="card-body">
                        <button class="btn btn btn-primary" @click="printReport('myTable')"><i class="fa fa-print"></i>
                        </button>

                        <div class="invoice-box" id="myTable">
                            <div class="information text-center">
                                <h3>{{ setting.name }}</h3>
                                <div>{{ setting.location }}</div>
                                <div>{{ setting.telephones }}</div>
                                <div>فاتوره رقم : {{ orderId }}</div>
                            </div>

                            <div v-for="(row, index) in rows">
                                <h4>{{ row.name }}</h4>

                                <table class="table text-center" cellpadding="0" cellspacing="0">
                                    <thead>
                                    <tr class="heading">
                                        <td>الرقم</td>
                                        <td>إسم المنتج</td>
                                        <td>سعر شراء الوحده</td>
                                        <td>الكميه</td>
                                        <td>الجمله</td>

                                    </tr>
                                    </thead>

                                    <tr class="item" v-for="(product, index) in row.products">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ product.name }}</td>
                                        <td>{{ formatPrice(product.purchase_price) }}</td>
                                        <td>{{ product.stock }}</td>
                                        <td>{{ formatPrice(product.stock * product.purchase_price) }}</td>
                                    </tr>

                                    <tr class="total heading">
                                        <td colspan="4">الجــــــــــــلـــمه</td>
                                        <td>{{ formatPrice(sumPrice[row.id]) }}</td>
                                        <!--                                        <td>{{ // sumPrice[row.id] }}</td>-->
                                        <!--                                        <td>{{ // sumStock[row.id] }}</td>-->
                                        <!--                                        <td>{{ sumStock[row.id] * sumPrice[row.id] }}</td>-->
                                    </tr>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="card mb-3" v-if="Object.keys(today).length != 0">
                    <div class="card-body">
                        <button class="btn btn btn-primary" @click="printReport('today')"><i class="fa fa-print"></i>
                        </button>
                        <div class="invoice-box" id="today">

                            <div class="information text-center">
                                <h3>{{ setting.name }}</h3>
                                <div>{{ setting.location }}</div>
                                <div>{{ setting.telephones }}</div>
                                <div>فاتوره رقم : {{ orderId }}</div>
                            </div>

                            <div v-for="(day, index) in today">
                                <h4>{{ day.name }}</h4>

                                <table class="table text-center" cellpadding="0" cellspacing="0">
                                    <thead>
                                    <tr class="heading">
                                        <td>الرقم</td>
                                        <td>إسم المنتج</td>
                                        <td>سعر شراء الوحده</td>
                                        <td>الكميه</td>
                                        <td>التخفيض</td>
                                        <td>المدفوع</td>
                                        <td>التاريخ</td>
                                    </tr>
                                    </thead>

                                    <tr class="item" v-for="(item, index) in day.orders">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.product_name }}</td>
                                        <td>{{ formatPrice(item.sale_price) }}</td>
                                        <td>{{ item.quantity }}</td>
                                        <td>{{ item.discount }}</td>
                                        <td>{{ formatPrice(item.paid_price) }}</td>
                                        <td>{{ item.created_at }}</td>
                                    </tr>

                                    <tr class="total heading">
                                        <td colspan="5">الجــــــــــــلـــمه</td>
                                        <td>{{ formatPrice(today_sum[day.id]) }}</td>
                                        <td></td>
                                    </tr>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card mb-3" v-if="Object.keys(expenses).length != 0">
                    <div class="card-body">
                        <button class="btn btn btn-primary" @click="printReport('expenses')"><i class="fa fa-print"></i>
                        </button>
                        <div class="invoice-box" id="expenses">
                            <h4>المصروفات</h4>

                            <table class="table text-center" cellpadding="0" cellspacing="0">
                                <thead>
                                <tr class="heading">
                                    <td>الرقم</td>
                                    <td>البند</td>
                                    <td>المبلغ</td>
                                    <td>التاريخ</td>
                                </tr>
                                </thead>

                                <tr class="item" v-for="(item, index) in expenses">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ formatPrice(item.price) }}</td>
                                    <td>{{ item.created_at }}</td>
                                </tr>

                                <tr class="total heading">
                                    <td colspan="2">الجــــــــــــلـــمه</td>
                                    <td>{{ formatPrice(expensesSum) }}</td>
                                    <td></td>
                                </tr>

                            </table>


                        </div>
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
            modalTitle: 'reports',
            routeName: 'report',
            title: 'التقارير',
            subtitle: 'تقرير',
            categorySearch: '',
            data_category: null,
            categoryName: '',
            rows: {},
            today: {},
            todayPaid: '',
            month: '',
            expense: '',
            expenses: {},
            setting: {},
            expensesSum: '',
            categories: {},
            sumPrice: {},
            sumStock: {},
            from_to_sum: {},
            today_sum: {},
            from_to: {},
            today_form: new Form({
                id: '',
                name: 'today',
                category_id: ''
            }),
            inventory_form: new Form({
                id: '',
                name: 'inventory',
                category_id: ''
            }),
            from_to_form: new Form({
                id: '',
                name: 'from_to',
                from: '',
                to: '',
                category_id: ''
            }),
            expenses_form: new Form({
                id: '',
                name: 'expenses',
                from: '',
                to: '',
                category_id: ''
            }),
        }
    },
    props: ['id'],
    methods: {
        printReport(name) {
            $('#' + name).printThis();
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        searchCategory() {
            if (this.categorySearch != '') {

                axios.get('api/category?name=' + this.categorySearch).then(({data}) => (this.categories = data));
            } else if (this.categorySearch == '') {
                axios.get('api/category').then(({data}) => (this.categories = data));
            }
        },
        putCategoryId(category) {
            $("#category-search").modal("hide");
            if (category === 'all') {
                this.categoryName = 'كل الأقسم';
                this.data_category = null;
                this.today_form.category_id = 'all';
                this.from_to_form.category_id = 'all';
                this.inventory_form.category_id = 'all';
            } else {
                this.categoryName = category.name;
                this.data_category = category.id;
                this.today_form.category_id = category.id;
                this.from_to_form.category_id = category.id;
                this.inventory_form.category_id = category.id;
            }

        },
        getResults(page = 1) {
            axios.get('api/category?page=' + page)
                .then(response => {
                    this.categories = response.data;
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
        createData(name) {
            this.$Progress.start();
            if (name == 'from_to') {
                this.from_to_form
                    .post("api/" + this.routeName)
                    .then(({data}) => {
                        this.from_to = {};
                        this.expenses = {};
                        this.today = {};
                        this.rows = {};
                        this.from_to = data.categories;
                        this.from_to_sum = data.from_to_sum;
                        this.expenses = data.expenses;
                        this.expensesSum = data.expensesSum;
                        // this.sumStock = data.sumStock;
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
            } else if (name == 'inventory_form') {
                this.inventory_form
                    .post("api/" + this.routeName)
                    .then(({data}) => {
                        this.from_to = {};
                        this.today = {};
                        this.expenses = {};
                        this.rows = {};
                        this.from_to = {};
                        this.rows = data.categories;
                        this.sumPrice = data.sumPrice;
                        // this.sumStock = data.sumStock;
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
            } else if (name == 'today_form') {
                this.today_form
                    .post("api/" + this.routeName)
                    .then(({data}) => {
                        this.from_to = {};
                        this.expenses = {};
                        this.rows = {};
                        this.today = data.categories;
                        this.today_sum = data.today_sum;

                        // this.sumPrice = data.sumPrice;
                        // this.sumStock = data.sumStock;
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
            }
        }
    },
    created() {

        this.loadData();
        axios.get('api/category').then(({data}) => (this.categories = data));
        axios.get('api/report?date=today').then(({data}) => (this.todayPaid = data));
        axios.get('api/report?date=month').then(({data}) => (this.month = data));
        axios.get('api/report?date=expense').then(({data}) => (this.expense = data));
        this.putCategoryId('all');

        axios.get('api/setting').then(({data}) => (this.setting = data));



    }
}
</script>
