<template>
    <v-container>
        <v-card class="mx-auto pb-4" outlined>
            <v-img height="250" src="/images/site_content/banner_11.jpg" />

            <v-card-title>Compte mensuel {{ monthLabelPronom }} {{ monthLabel }} {{ month.annee }}</v-card-title>

            <v-card-text>
                <v-row align="center" class="mx-0">
                    <v-rating
                        :value="4.5"
                        color="amber"
                        dense
                        half-increments
                        readonly
                        size="14">
                    </v-rating>

                    <div class="grey--text ms-4">
                        4.5 (413)
                    </div>
                </v-row>

                <div v-if="monthDataLoading" class="my-4 text-subtitle-1">
                    <v-skeleton-loader class="mx-auto" type="heading" />
                </div>
                <div v-else class="my-4 text-subtitle-1">
                      Solde au {{ this.now }} : {{ this.solde }}
                </div>

                <div>Opérations du mois en cours</div>
            </v-card-text>
            <v-divider class="mx-4"></v-divider>
            <v-data-table
                :headers="headers"
                :items="month_data"
                item-key="month_data_id"
                class="elevation-1 mx-4 my-4">
            </v-data-table>
        </v-card>
    </v-container>
</template>

<script>
import axios from 'axios';
import * as moment from 'moment';

export default {
    name: "MonthDetail",

    data: () => ({
        month: [],
        month_data: [],
        monthDataLoading : true,
        solde: 0,
        isFetching: true,
        headers: [
            { text: 'Date', value: 'dateOperation' },
            { text: 'Rubrique', value: 'rubrique' },
            { text: 'Libellé', value: 'libelle' },
            { text: 'Crédit', value: 'credit' },
            { text: 'Debit', value: 'debit' },
            { text: 'Pointée ?', value: 'estPointee' },
        ],
        now: 0,
        monthLabel: '',
        monthLabelPronom: "de",
    }),

    computed: {

    },

    methods: {
        /*
         *  Initialise toutes les variables nécessaires à la page
        */
        init() {
            moment.locale('fr');
            this.now = moment().format('dddd Do MMMM YYYY')
        },

        /*
         *  Initialise les libellés en rapport avec le mois en cours.
         *  Modifie également le pronom si nécessaire devant le nom du mois en cours
        */
        initMonthLabel(mois) {
            this.monthLabel = moment().month(mois - 1).format("MMMM"); // On récupère le libellé du mois en cours (Attention, range des mois 0-11)
            if (mois == 8 || mois == 10) this.monthLabelPronom = "d'"; // Pour les mois d'aout et octobre, on change le pronom
        },

        /*
         *  Récupère toutes les données nécessaires à la création de la page
         *  - Toutes les opérations du mois
         *  - Le solde actuel du mois
         *  - Le libellé du mois actuel
        */
        async getData() {
            // On récupère toutes les URL des opérations d'un mois en particulier
            let response = await axios.get('/api/months/'+this.$route.params.id)
                .then((response) => {
                    this.month = response.data;
                    this.initMonthLabel(this.month.mois); // On récupère le libellé du mois en cours
                    // On parcourt toutes les URL des opérations d'un mois pour récupérer les données de chaqune
                    this.getAllLines()
                        .then(() => this.monthDataLoading = false)
                        .finally(() => this.getSolde());
                })
                .catch(error => { console.log("Error : " + error); });
            this.isFetching = false; // On a finit de tout récupérer
        },

        /*
         *  Récupère via l'API une ligne du mois
         *  @param: lineId => identifiant de la ligne à récupérer
        */
        async getLine(lineId) {
            let responseLine = await axios
                .get(lineId)
                .then(responseLine => {
                    this.month_data.push(responseLine.data);
                })
                .catch(error => { console.log("Error in nested Axios : " + error); });
        },

        /*
         *  Récupère toutes les lignes d'un mois
        */
        async getAllLines() {
            for (let i=0; i<this.month.allLines.length; i++) {
                await this.getLine(this.month.allLines[i]);
            }
        },

        /*
         *  Calcul et retourne le solde du mois
        */
        getSolde() {
            for (let i=0; i<this.month_data.length; i++) {
                this.solde += this.month_data[i].credit ?? 0; // On récupère le crédit de la ligne
                this.solde -= this.month_data[i].debit ?? 0; // On récupère le débit de la ligne
            }
            this.solde = this.convertEUR(this.solde);
            console.log("Solde : " + this.solde + " | type : " + typeof(this.solde));
        },

        /*
         *  Retourne un nombre au format EUR
        */
        convertEUR(nombre) {
            const currency = function(number){
                return new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR', minimumFractionDigits: 2}).format(number);
            };
            return currency(nombre);
        }
    },

    created() {
        this.init();
        this.getData();
    }
};
</script>
