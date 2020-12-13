<template>
    <div>

        <div class="row">
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <button @click="loadData(); loadDeletedData();" class="btn btn-info"><i
                                    class="fa fa-edit"></i></button>
                                <button @click="loadExpenseData(); loadDeletedExpenseData();" class="btn btn-success"><i
                                    class="fa fa-money"></i></button>
                            </div>
                            <div class="col-8">
                                <h3>{{ title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-5">
                <div class="row">
                    <div class="col-12" v-show="orderMode">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>الفواتير المعدله</h4>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" placeholder="بحث ...." class="form-control"
                                               v-model="orderSearch" @keyup="loadData()">
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
                                <pagination :limit="5" :data="rows" @pagination-change-page="getResults"></pagination>

                            </div>
                        </div>
                    </div>

                    <div class=" col-12" v-show="orderMode">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>الفواتير المحذوفه</h4>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" placeholder="بحث ...." class="form-control"
                                               v-model="deletedOrderSearch" @keyup="loadDeletedData()">
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
                                    <tr v-for="(row, index) in deletedRows.data" :key="row.id">
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
                                <pagination :limit="5" :data="deletedRows"
                                            @pagination-change-page="getDeletedResults"></pagination>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-2" v-show="expenseMode">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>المصروفات المعدله</h4>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" placeholder="بحث ...." class="form-control"
                                               v-model="expenseSearch" @keyup="loadExpenseData()">
                                    </div>
                                </div>

                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="text-center">
                                        <th>المستخدم</th>
                                        <th>البند</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>التحكم</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in expenseRows.data" :key="row.id">
                                        <td>{{ row.user.name }}</td>
                                        <td>{{ row.name }}</td>
                                        <td>{{ row.price }}</td>
                                        <td>{{ row.created_at }}</td>
                                        <td>
                                            <a href="#" @click="showExpense(row.id)"><i class="fa fa-eye red"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <pagination :limit="5" :data="expenseRows" @pagination-change-page="getResults"></pagination>

                            </div>
                        </div>
                    </div>

                    <div class="col-12" v-show="expenseMode">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>المصروفات المحذوفه</h4>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" placeholder="بحث ...." class="form-control"
                                               v-model="deletedExpenseSearch" @keyup="loadDeletedExpenseData()">
                                    </div>
                                </div>

                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="text-center">
                                        <th>المستخدم</th>
                                        <th>البند</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>التحكم</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in deletedExpenses.data" :key="row.id">
                                        <td>{{ row.user.name }}</td>
                                        <td>{{ row.name }}</td>
                                        <td>{{ row.price }}</td>
                                        <td>{{ row.created_at }}</td>
                                        <td>
                                            <a href="#" @click="showExpense(row.id)"><i class="fa fa-eye red"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <pagination :limit="5" :data="expenseRows" @pagination-change-page="getResults"></pagination>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-7" v-if="Object.keys(orders).length != 0">
                <div class="row">

                    <div class="col-12 mb-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-4"><h4 class="card-title"><span>رقم الفاتوره : </span>{{
                                            orders.id
                                        }}</h4></div>
                                    <div class="col-5"><span>التاريخ : </span>{{ orders.created_at }}</div>
                                    <div class="col-3"><span>المستخدم : </span>{{ orders.user.name }}</div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="text-center">
                                        <th>إسم المنتج</th>
                                        <th>سعر الوحده</th>
                                        <th>الكميه</th>
                                        <th>التخفيض</th>
                                        <th>المدفوع</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(detail, index) in orders.details" :key="detail.id">
                                        <td>{{ detail.product.name }}</td>
                                        <td>{{ detail.sale_price }}</td>
                                        <td>{{ detail.quantity }}</td>
                                        <td>{{ detail.discount }}</td>
                                        <td>{{ detail.paid_price }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-2" v-for="(order, index) in orders.orders_update">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-4"><h4 class="card-title">
                                        <span>رقم الفاتوره : </span>{{ order.order_id }}</h4></div>
                                    <div class="col-5"><span>التاريخ : </span>{{ order.created_at }}</div>
                                    <div class="col-3"><span>المستخدم : </span>{{ order.user.name }}</div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table id="table_id" class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="text-center">
                                        <th>إسم المنتج</th>
                                        <th>سعر الوحده</th>
                                        <th>الكميه</th>
                                        <th>التخفيض</th>
                                        <th>المدفوع</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(detail, index) in order.update_details" :key="detail.id">
                                        <td>{{ detail.product.name }}</td>
                                        <td>{{ detail.sale_price }}</td>
                                        <td>{{ detail.quantity }}</td>
                                        <td>{{ detail.discount }}</td>
                                        <td>{{ detail.paid_price }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-7" v-if="Object.keys(deletedOrders).length != 0">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-4"><h4 class="card-title">
                                        <span>رقم الفاتوره : </span>{{ deletedOrders.id }}</h4></div>
                                    <div class="col-5"><span>التاريخ : </span>{{ deletedOrders.deleted_at }}</div>
                                    <div class="col-3"><span>المستخدم : </span>{{ deletedOrders.user.name }}</div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="text-center">
                                        <th>إسم المنتج</th>
                                        <th>سعر الوحده</th>
                                        <th>الكميه</th>
                                        <th>التخفيض</th>
                                        <th>المدفوع</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(detail, index) in deletedOrders.details" :key="detail.id">
                                        <td>{{ detail.product.name }}</td>
                                        <td>{{ detail.sale_price }}</td>
                                        <td>{{ detail.quantity }}</td>
                                        <td>{{ detail.discount }}</td>
                                        <td>{{ detail.paid_price }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-7" v-if="Object.keys(expenses).length != 0">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-5"><h4></h4></div>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr class="text-center">
                                        <th>المستخدم</th>
                                        <th>البند</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ expenses.user.name }}</td>
                                        <td>{{ expenses.name }}</td>
                                        <td>{{ expenses.price }}</td>
                                        <td>{{ expenses.created_at }}</td>
                                    </tr>
                                    <tr v-for="(detail, index) in expenses.expenses_update" :key="detail.id">
                                        <td>{{ detail.user.name }}</td>
                                        <td>{{ detail.name }}</td>
                                        <td>{{ detail.price }}</td>
                                        <td>{{ detail.created_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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
            orderMode: false,
            expenseMode: false,
            modalTitle: 'control',
            routeName: 'control',
            title: '',
            subtitle: 'عميل',
            searchClient: '',
            allPermissions: {},
            rows: {},
            deletedRows: {},
            orders: {},
            deletedOrders: {},
            expenses: {},
            orderSearch: '',
            deletedOrderSearch: '',
            expenseSearch: '',
            deletedExpenseSearch: '',
            expenseRows: {},
            deletedExpenses: {},
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
        getDeletedResults(page = 1) {
            axios.get('api/' + this.routeName + '/deleted?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },

        loadData() {
            this.orderMode = true;
            this.expenseMode = false;
            this.expenseRows = {};
            this.expenses = {};
            this.deletedExpenses = {};

            this.title = 'الفواتير المعدله والمحذوفه';
            if (this.orderSearch != '') {
                axios.get('api/' + this.routeName + '?search=' + this.orderSearch).then(({data}) => (this.rows = data));
            } else {
                axios.get('api/' + this.routeName).then(({data}) => (this.rows = data));
            }
        },
        loadDeletedData() {
            this.expenseRows = {};
            this.expenses = {};
            this.deletedExpenses = {};

            if (this.deletedOrderSearch != '') {
                axios.get('api/' + this.routeName + '/deleted?search=' + this.deletedOrderSearch).then(({data}) => (this.deletedRows = data));
            } else {
                axios.get('api/' + this.routeName + '/deleted').then(({data}) => (this.deletedRows = data));
            }
        },
        loadExpenseData() {
            this.orderMode = false;
            this.expenseMode = true;
            this.orders = {};
            this.deletedRows = {};
            this.rows = {};
            this.deletedOrders = {};

            this.title = 'المصروفات المحذوفه والمعدله';
            if (this.expenseSearch != '') {
                axios.get('api/' + this.routeName + '/expenses?search=' + this.expenseSearch).then(({data}) => (this.expenseRows = data));
            } else {
                axios.get('api/' + this.routeName + '/expenses').then(({data}) => (this.expenseRows = data));
            }
        },
        loadDeletedExpenseData() {
            this.orderMode = false;
            this.expenseMode = true;
            this.orders = {};
            this.deletedRows = {};
            this.rows = {};
            this.deletedOrders = {};

            this.title = 'المصروفات المحذوفه والمعدله';
            if (this.deletedExpenseSearch != '') {
                axios.get('api/' + this.routeName + '/deletedExpenses?search=' + this.deletedExpenseSearch).then(({data}) => (this.deletedExpenses = data));
            } else {
                axios.get('api/' + this.routeName + '/deletedExpenses').then(({data}) => (this.deletedExpenses = data));
            }
        },
        showOrder(id) {
            axios.get('api/' + this.routeName + '?order=' + id).then(({data}) => (this.orders = data));
        },

        showExpense(id) {
            axios.get('api/' + this.routeName + '/expenses?expense=' + id).then(({data}) => (this.expenses = data));
        },

    },
    created() {
        //
    }
}
</script>
