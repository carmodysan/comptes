<template>
    <v-app v-cloak>
        <v-navigation-drawer permanent clipped app>
            <v-list-item>
                <v-list-item-content>
                  <v-list-item-title class="text-h6">
                    Application
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    subtext
                  </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>
            <v-list dense nav>
                <v-list-item-group v-model="selectedItem" color="primary">
                    <v-list-item
                        v-for="(item, i) in items"
                        :key="i"
                        :to="item.to">
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title v-text="item.text"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar short clipped-left app>
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
            <v-toolbar-title>Mon site perso</v-toolbar-title>
        </v-app-bar>
        <v-main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
export default {
    name: "App",
    data: () => ({
        selectedItem: 0,
        items: [
            { text: 'My Files', icon: 'mdi-folder', to: '/home' },
            { text: 'Shared with me', icon: 'mdi-account-multiple', to: '/test' },
            { text: 'Starred', icon: 'mdi-star', to: '/admin' },
            { text: 'Recent', icon: 'mdi-history', to: '/months' },
            { text: 'Offline', icon: 'mdi-check-circle' },
            { text: 'Uploads', icon: 'mdi-upload' },
            { text: 'Backups', icon: 'mdi-cloud-upload', to: '/carrousel' },
        ],
    }),
};
</script>
