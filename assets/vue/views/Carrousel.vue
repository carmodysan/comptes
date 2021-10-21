<template>
    <div>
        <v-img
                max-width="600"
                max-height="600"
                lazy-src="/images/under-construction.gif"
                src="/images/under-construction.gif" />
        <v-carousel>
            <v-carousel-item
                cycle
                interval="3000"
                max-width="600"
                max-height="600"
                v-for="(item,i) in items"
                :key="i"
                :src="item.src"
                reverse-transition="fade-transition"
                transition="fade-transition"
            ></v-carousel-item>
        </v-carousel>
    </div>
</template>

<script>
export default {
    name: "Carrousel",

    data() {
        return {
            items: [
                { src: '/images/a2m.gif' },
                { src: '/images/a2m2.gif' },
                { src: '/images/gthong.gif' },
            ]
        }
    }
}
</script>
