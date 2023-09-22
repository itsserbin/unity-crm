<template>
    <a v-if="isHyperLink && !item.href" v-bind="$attrs">
        <slot/>
    </a>
    <Link v-else v-bind="$attrs" :href="item.href || 'javascript:;'" @click="item.navigate">
        <slot/>
    </Link>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import {router} from "@inertiajs/vue3";

export default {
    name: "SidebarMenuLink",
    inheritAttrs: false,
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    components: {Link},
    computed: {
        isHyperLink() {
            return !this.item.href || this.item.external || !router;
            // return !this.item.href || this.item.external || !this.$router;
        },
    },
};
</script>
