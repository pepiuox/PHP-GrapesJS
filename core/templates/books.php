<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
    <!-- BootstrapVue CSS -->
    <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css" />
    <!-- Bootstrap npm CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>VUEJS CRUD With PHP With Source Code</title>
  </head>
  <body class="bg-white">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #6a5acd;">
      <div class="container-fluid">
        <a class="navbar-brand" href="https://www.onlyxcodes.com/">Onlyxcodes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="https://www.onlyxcodes.com/2021/06/vuejs-crud-with-php-mysql.html">Back to Tutorial</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container" id="bookApp">
      <div class="row">
        <div class="col-md-8 mx-auto my-5">
          <!-- Below Bootstrap Alert dialog show record Insert and Update Messages -->
          <div v-if="successMsg">
            <b-alert show variant="success"> {{ successMsg }} </b-alert>
          </div>
          <div class="card mt-5">
            <div class="card-header">
              <div class="d-flex d-flex justify-content-between">
                <div class="lead">
                  <button id="show-btn" @click="showModal('add-modal')" class="btn btn-sm btn-outline-primary">Add Records</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Add Book Record Modal Start-->
              <b-modal ref="add-modal" hide-footer title="Add Book Records">
                <form action="" @submit.prevent="onSubmit">
                  <div class="form-group">
                    <label for="">Book Name</label>
                    <input type="text" v-model="name" class="form-control">
                  </div>
                  <br />
                  <div class="form-group">
                    <label for="">Author</label>
                    <input type="text" v-model="author" class="form-control">
                  </div>
                  <br />
                  <div class="form-group">
                    <button class="btn btn-sm btn-outline-success">Add Records</button> &nbsp; <b-button class="btn btn-sm btn-outline-danger" variant="outline-danger" block @click="hideModal('add-modal')">Close </b-button>
                  </div>
                </form>
              </b-modal>
              <!-- Add Book Record Modal End -->
              <!-- table start-->
              <div class="text-muted lead text-center" v-if="!allRecords.length">No record found</div>
              <div class="table-responsive" v-if="allRecords.length">
                <table class="table table-borderless">
                  <thead class="table-dark">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Author</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(record, i) in allRecords" :key="record.book_id">
                      <td>{{i + 1}}</td>
                      <td>{{record.book_name}}</td>
                      <td>{{record.book_author}}</td>
                      <td>
                        <button @click="deleteRecord(record.book_id)" class="btn btn-sm btn-outline-danger">Delete</button>
                        <button @click="editRecord(record.book_id)" class="btn btn-sm btn-outline-warning">Edit</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- table end -->
              </div>
            </div>
          </div>
          <!-- Update Book Record Modal Start -->
          <div>
            <b-modal ref="update-modal" hide-footer title="Update Book Record">
              <div>
                <form action="" @submit.prevent="onUpdate">
                  <div class="form-group">
                    <label for="">Book Name</label>
                    <input type="text" v-model="update_name" class="form-control">
                  </div>
                  <br />
                  <div class="form-group">
                    <label for="">Author</label>
                    <input type="text" v-model="update_author" class="form-control">
                  </div>
                  <br />
                  <div class="form-group">
                    <button class="btn btn-sm btn-outline-primary">Update Record</button> &nbsp; <b-button class="btn btn-sm btn-outline-danger" variant="outline-danger" block @click="hideModal('update-modal')">Close </b-button>
                  </div>
                </form>
              </div>
            </b-modal>
          </div>
          <!-- Update Book Record Modal End -->
        </div>
      </div>
    </div>
    <!-- Vuejs -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- BootstrapVue js -->
    <script type="text/javascript" src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>
    <!-- Axios -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
      var application = new Vue({
        el: '#bookApp',
        data: {
          name: '',
          author: '',
          allRecords: [],
          update_id: '',
          update_name: '',
          update_author: '',
          successMsg: ''
        },
        methods: {
          showModal(id) {
            this.$refs[id].show()
          },
          hideModal(id) {
            this.$refs[id].hide()
          },
          onSubmit() {
            if (this.name !== '' && this.author !== '') {
              var fd = new FormData() fd.append('name', this.name) fd.append('author', this.author) axios({
                url: 'create.php',
                method: 'post',
                data: fd
              }).then(response => {
                if (response.data.result == 'success') {
                  application.successMsg = 'record insert successfully';
                  this.name = ''
                  this.author = ''
                  application.hideModal('add-modal') application.getRecords()
                } else {
                  alert('error ! record inserting prolem ')
                }
              }).catch(error => {
                console.log(error)
              })
            } else {
              alert('sorry ! all fields are required')
            }
          },
          getRecords() {
            axios({
              url: 'read.php',
              method: 'get'
            }).then(response => {
              this.allRecords = response.data.rows
            }).catch(error => {
              console.log(error)
            })
          },
          deleteRecord(id) {
            if (window.confirm('Are you sure want to delete this record ?')) {
              var fd = new FormData() fd.append('id', id) axios({
                url: 'delete.php',
                method: 'post',
                data: fd
              }).then(response => {
                if (response.data.result == 'success') {
                  application.successMsg = 'record delete successfully';
                  application.getRecords();
                } else {
                  alert('sorry ! record not deleting')
                }
              }).catch(error => {
                console.log(error)
              })
            }
          },
          editRecord(id) {
            var fd = new FormData() fd.append('id', id) axios({
              url: 'edit.php',
              method: 'post',
              data: fd
            }).then(response => {
              if (response.data.result == 'success') {
                this.update_id = response.data.row[0] this.update_name = response.data.row[1] this.update_author = response.data.row[2] application.showModal('update-modal')
              }
            }).catch(error => {
              console.log(error)
            })
          },
          onUpdate() {
            if (this.update_name !== '' && this.update_author !== '' && this.update_id !== '') {
              var fd = new FormData() fd.append('id', this.update_id) fd.append('name', this.update_name) fd.append('author', this.update_author) axios({
                url: 'update.php',
                method: 'post',
                data: fd
              }).then(response => {
                if (response.data.result == 'success') {
                  application.successMsg = 'record update successfully';
                  this.update_name = '';
                  this.update_author = '';
                  this.update_id = '';
                  application.hideModal('update-modal');
                  application.getRecords();
                }
              }).catch(error => {
                console.log(error)
              })
            } else {
              alert('sorry ! all fields are required')
            }
          }
        },
        mounted: function() {
          this.getRecords()
        }
      })
    </script>
  </body>
</html>