<template>
    <v-container>
        <div v-if="!allLoaded">
            <div
                v-for="month in months"
                :key="month['@id']"
            >
            </div>
        </div>

        <div v-else>
            <v-expansion-panels accordion>
                <v-expansion-panel v-for="(months, index) in ihmArrMonths" :key="index" class="mx-auto">
                    <v-expansion-panel-header>{{ months[0] }}</v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-card v-for="month in months[1].reverse()" :key="month['@id']" class="mx-auto my-5" elevation="4" outlined>
                                <v-card-title>{{ month.annee }} {{ month.mois }}</v-card-title>
                                <v-card-subtitle>Nombre d'opérations : {{ month.allLines.length }}</v-card-subtitle>
                                <v-card-text>Petite description du mois</v-card-text>
                                <v-card-action>
                                    <v-btn
                                        color="deep-purple lighten-2"
                                        text
                                        :to="'/month/' + month.id"
                                    >
                                    Détails
                                    </v-btn>
                                </v-card-action>
                            </v-card>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels accordion>
        </div>
    </v-container>
</template>

<script>
import axios from 'axios';

export default {
    name: "Months",

    data: () => ({
        months: null,
        allLoaded: false,
        ihmArrMonths: [],
    }),

    methods: {
        /*
         *  Initialisation des objets nécessaires à la page
        */
        async init() {
            await axios.get('/api/months')
                .then((response) => {
                    this.months = response.data['hydra:member'];
                })
                .finally(() => {
                    this.convertMonthsToObject();
                    this.allLoaded = true;
                });
        },

        /*
         *  Converti le tableau en provenance de l'API en un tableau exploitable pour l'IHM
         * [
         * [2021, Month object],
         * [2020, Month object],
         * etc.
         * ]
        */
        convertMonthsToObject() {
            // On tri tous les mois du plus récent au plus ancien
            this.months.sort(function compare(a, b) {
                // On transforme l'année et le mois en un chiffre comparable pour pouvoir trier tous les objets Month de la base
                var aC = a.annee * 100 + a.mois;
                var bC = b.annee * 100 + b.mois;
                if (aC < bC)
                    return 1;
                if (aC > bC)
                    return -1;
                return 0;
            });

            // On boucle sur tous les mois maintenant rangés dans l'ordre du plus récent au plus ancien (cf. ligne du dessus)
            // Tant qu'on ne change pas d'année, on remplit un tableau temporaire.
            // Dès qu'on change d'année, on inscrit dans le tableau complet IHM, un nouveau tableau à deux entrées:
            // - année
            // - tableau temporaire de l'année
            // On vide le tableau temporaire et on recommence à y inscrire le mois de la nouvelle année
            // Pour le dernier tour de boucle, on inscrit le tableau temporaire (qui correspond à la dernière année à enregistrer) dans le tableau complet IHM
            let isNewYear = false;
            let precYear = -1;
            let tmpArr = [];
            for (let i=0; i<this.months.length; i++) {
                // On teste si c'est une nouvelle année en regardant la précédente valeur année du mois de la boucle précédente
                precYear != this.months[i].annee ? isNewYear = true : isNewYear = false;
                if (isNewYear) {
                    // Ajout du mois dans le tableau complet IHM si nous changeons d'année et qu'il y a des résultats dans le tableau temporaire
                    if (tmpArr.length != 0) {
                        this.ihmArrMonths.push([precYear, tmpArr]);
                        tmpArr = [];
                    }
                }
                // On inscrit le mois dans le tableau temporaire
                tmpArr.push(this.months[i]);

                // On inscrit l'année pour connaitre l'année précédente lors du prochain tour de boucle
                precYear = this.months[i].annee;

                // Si c'est le dernier tour de boucle on peut enregistrer le tableau temporaire dans le tableau complet IHM. Ceka correspond à la dernière année à
                // enregistrer qui ne sera pas fait par la boucle for.
                if (i == this.months.length-1 && tmpArr.length != 0)
                    this.ihmArrMonths.push([precYear, tmpArr]);
            }
            console.log(this.ihmArrMonths);
        },
    },

    created() {
        this.init();
    }
};
</script>
