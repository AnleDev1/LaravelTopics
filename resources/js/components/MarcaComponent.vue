<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="float-start text-center pt-2">Listado de Marcas</h4>
                            </div>
                            <div class="col-6">
                                <button @click="showDialog()"
                                    class="btn btn-success btn-toolbar float-end">Nuevo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Nombre</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in marcas" :key="item.id">
                                    <td class="text-center">{{ item.nombre }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-primary" @click="showDialogEditar(item)"
                                            style="margin-right: 20px;">Editar</button>
                                        <button class="btn btn-danger" @click="eliminar(item)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="marcaModal" tabindex="-1" aria-labelledby="marcaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="marcaModalLabel">{{ formTitle }}</h4>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--Definición del cuerpo del modal-->
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" v-model="marca.nombre">
                            <span class="text-danger" v-show="marcaErrors.nombre">Nombre es obligatorio</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button @click="saveOrUpdate()" type="button" class="btn btn-primary">{{ btnTitle }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            marcas: [],
            marca: {
                id: null,
                nombre: ""
            },
            editedMarca: -1,
            marcaErrors: {
                nombre: false
            }
        }
    },
    computed: {
        formTitle() {
            return this.marca.id == null ? "Agregar Marca" : "Actualizar Marca";
        },
        btnTitle() {
            return this.marca.id == null ? "Guardar" : "Actualizar";
        }
    },
    methods: {
        async fetchMarcas() {
            let me = this;
            await me.axios.get('/marcas')
                .then(response => {
                    me.marcas = response.data;

                }).catch(error => {
                    console.log(error);
                })
        },
        async saveOrUpdate() {
            let me = this;
            me.marca.nombre == '' ? me.marcaErrors.nombre = true : false;
            if (me.marca.nombre) {
                let accion = me.marca.id == null ? "add" : "udp";
                if (accion == "add") {
                    //Guardar una marca
                    await this.axios.post('/marcas', me.marca)
                        .then(response => {
                            this.verificarAccion(response.data.data, response.status, accion);
                            this.hideDialog();
                        }).catch(errors => {
                            console.log(errors);
                        })

                } else {
                    //Actualizar marca
                    await this.axios.put(`/marcas/${me.marca.id}`, me.marca)
                        .then(response => {
                            if (response.status == 201) {
                                me.verificarAccion(response.data.data, response.status, accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            }
        },
        async eliminar(marca) {
            let me = this;
            this.$swal.fire({
                title: '¿Esta seguro de eliminar este registro?',
                text: 'Esta accion es permanente',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    me.editedMarca = me.marcas.indexOf(marca)
                    this.axios.delete(`/marcas/${marca.id}`)
                        .then(response => {
                            me.verificarAccion(null, response.status, "del")
                        }).catch(error => {
                            console.log(error);
                        })
                }
            })
        },
        showDialog() {
            this.marca = {
                id: null,
                nombre: ""
            },
                this.marcaErrors = {
                    nombre: false
                },
                $('#marcaModal').modal('show');
        },
        showDialogEditar(marca) {
            let me = this;
            $('#marcaModal').modal('show');
            me.editedMarca = me.marcas.indexOf(marca)
            me.marca = Object.assign({}, marca);
        },
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.marca = {
                    id: null,
                    nombre: ""
                }
            }, 300)
            $('#marcaModal').modal('hide');
        },
        verificarAccion(marca, status, accion) {
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position: 'top-end',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            });
            switch (accion) {
                case "add":
                    me.marcas.unshift(marca);
                    Toast.fire({
                        icon: 'success',
                        title: 'Marca Registrada'
                    });
                    break;
                case "udp":
                    Object.assign(me.marcas[me.editedMarca], marca);
                    Toast.fire({
                        icon: 'success',
                        title: 'Marca Actualizada'
                    });
                    break;
                case "del":
                    if (status == 200) {
                        try {
                            me.marcas.splice(me.editedMarca, 1);
                            Toast.fire({
                                icon: 'success',
                                title: 'Marca Eliminada'
                            });
                        } catch (error) {
                            console.log(error)
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Ocurrio un error'
                        });
                    }
                    break;
            }
        }
    },
    mounted() {
        //console.log('Component mounted.')
        //this.$swal('Hello Vue world!!!');
        this.fetchMarcas();
    }
}
</script>
