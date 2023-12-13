var app = new Vue({
                el: "#app",
                data: {
                    showmodaladd: false,
                    showmodaledit: false,
                    showmodaldelete: false,
                    successmessage: "",
                    errormessage: "",
                    datos: [],
                    newDato: {sort :"" , page_id :"" , title_page :"" , link_page :"" , parent_id :""},
                    clickedDato: {}

                },
                mounted: function () {
                    console.log("mounted");
                    this.getAllDatos();
                },
                methods: {
                    getAllDatos: function () {
                        axios.get("app.php?action=read")
                                .then(function (response) {
                                    // console.log(response);
                                    if (response.data.error) {
                                        app.errormessage = response.data.message;
                                    } else {
                                        app.datos = response.data.datos;
                                    }
                                });

                    },
                    saveDato: function () {
                        // console.log(app.newDato);
                        var formData = app.toformData(app.newDato);
                        axios.post("app.php?action=create", formData)
                                .then(function (response) {

                                    // app.newDato={sort :"" , page_id :"" , title_page :"" , link_page :"" , parent_id :""};

                                    if (response.data.error == true) {
                                        app.errormessage = response.data.message;
                                    } else {
                                        app.successmessage = response.data.message;
                                        app.getAllDatos();
                                    }
                                });
                    },
                    selectDato: function (dato) {
                        app.clickedDato = dato;
                    },
                    updateDato: function (dato) {
                        var formData = app.toformData(app.clickedDato);
                        axios.post("app.php?action=update", formData)
                                .then(function (response) {

                                    app.clickedDato = {};

                                    if (response.data.error) {
                                        app.errormessage = response.data.error;
                                    } else {
                                        app.successmessage = response.data.message;
                                        app.getAllDatos();
                                    }
                                });
                    },
                    deleteDato: function (dato) {
                        var formData = app.toformData(app.clickedDato);
                        axios.post("app.php?action=delete", formData)
                                .then(function (response) {

                                    app.clickedDato = {};

                                    if (response.data.error) {
                                        app.errormessage = response.data.message;
                                    } else {
                                        app.successmessage = response.data.message;
                                        app.getAllDatos();
                                    }
                                });
                    },
                    toformData: function (obj) {
                        var form_data = new FormData();
                        for (var key in obj) {
                            form_data.append(key, obj[key]);
                        }
                        return form_data;
                    },

                    clearMessage: function () {
                        app.successmessage = "";
                        app.errormessage = "";
                    }
                }

            });