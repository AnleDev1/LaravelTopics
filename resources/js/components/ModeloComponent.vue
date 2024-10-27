<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="6" class="" >
                <v-data-table :headers="columnas" :items="modelos" :items-per-page="5" class="text-md "
                    :pagination.sync="pagination" :prev-icon="'mdi-chevron-left'" :next-icon="'mdi-chevron-right'"
                    :first-icon="'mdi-page-first'" :last-icon="'mdi-page-last'">
                    <template v-slot:top>
                        <v-toolbar flat color="blue">
                            <v-toolbar-title class="text-h5">Modelos</v-toolbar-title>
                            <v-divider color="black" class="mx-2" inset vertical></v-divider>
                            <v-btn color="black" dark small @click="openDialog">Nuevo Modelo</v-btn>
                        </v-toolbar>
                    </template> 
                    <template v-slot:item.actions="{ item }">
                        <v-icon color="blue" size="large" class="" @click="editItem(item)">mdi-pencil</v-icon>
                        &nbsp;
                        <v-icon color="red" size="large" class=""
                            @click="eliminar(item)"> mdi-delete</v-icon>
                    </template>
                </v-data-table>

                <!-- Diálogos de Nuevo Modelo y Confirmación de Eliminación -->
                <v-dialog v-model="dialog" max-width="350px">
                    <v-card>
                        <v-card-title>{{ formTitle }}</v-card-title>
                        <v-card-text>
                            <v-text-field v-model="modelo.nombre" label="Nombre"></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn @click="close">Cancelar</v-btn>
                            <v-btn @click="save">Guardar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!--<v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title>¿Eliminar item?</v-card-title>
                        <v-card-actions>
                            <v-btn @click="closeDelete">Cancelar</v-btn>
                            <v-btn @click="deleteItemConfirm">Aceptar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>-->
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            dialogDelete: false,
            editedIndex: -1,
            modelo: { id: null, nombre: "" },
            modelos: [],
            columnas: [
                { title: "Nombre", value: "nombre" },
                { title: "Acciones", value: "actions", class: 'text-h1' }
            ]
        };
    },
    computed: {
        formTitle() { return this.editedIndex === -1 ? 'Nuevo Modelo' : 'Editar Modelo'; }
    },
    methods: {
        async fetchModelos() {
            try {
                const response = await this.axios.get('/modelos');
                this.modelos = response.data;
            } catch (error) {
                console.error("Error fetching modelos:", error);
            }
        },
        openDialog() {
            this.dialog = true;
            this.editedIndex = -1;
            this.modelo = { id: null, nombre: '' };
        },
        close() {
            this.dialog = false;
            this.modelo = { id: null, nombre: '' };
        },
        async save() {
            let me = this;
            if (me.modelo.nombre) {
                let accion = me.modelo.id == null ? "add" : "udp";
                if (accion == "add") {
                    //Guardar una modelo
                    await this.axios.post('/modelos', me.modelo)
                        .then(response => {
                            this.verificarAccion(response.data.data, response.status, accion);
                            this.close();
                        }).catch(errors => {
                            console.log(errors);
                        })

                } else {
                    //Actualizar modelo
                    await this.axios.put(`/modelos/${me.modelo.id}`, me.modelo)
                        .then(response => {
                            if (response.status == 201) {
                                me.verificarAccion(response.data.data, response.status, accion);
                                me.close();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            }
        },
        editItem(item) {
            this.editedIndex = this.modelos.indexOf(item);
            this.modelo = Object.assign({}, item);
            this.dialog = true;
        },
        verificarAccion(modelo, status, accion) {
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
                    me.modelos.unshift(modelo);
                    Toast.fire({
                        icon: 'success',
                        title: 'modelo Registrada'
                    });
                    break;
                case "udp":
                    Object.assign(me.modelos[me.editedIndex], modelo);
                    Toast.fire({
                        icon: 'success',
                        title: 'modelo Actualizada'
                    });
                    break;
                case "del":
                    if (status == 200) {
                        try {
                            me.modelos.splice(me.editedIndex, 1);
                            Toast.fire({
                                icon: 'success',
                                title: 'modelo Eliminada'
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
        },
        async eliminar(modelo) {
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
                    me.editedMarca = me.modelos.indexOf(modelo)
                    this.axios.delete(`/modelos/${modelo.id}`)
                        .then(response => {
                            me.verificarAccion(null, response.status, "del")
                        }).catch(error => {
                            console.log(error);
                        })
                }
            })
        },
    },
    mounted() {
        this.fetchModelos();
    }
};
</script>
