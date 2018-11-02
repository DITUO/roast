<style lang="scss" scoped>
    @import '~@/abstracts/_variables.scss';
</style>

<template>
    <div id="admin-layout">
        <success-notification></success-notification>
        <error-notification></error-notification>
    </div>
</template>

<script>
    import SuccessNotification from '../components/global/SuccessNotification.vue';
    import ErrorNotification from '../components/global/ErrorNotification.vue';
    
    export default {
        components: {
            SuccessNotification,
            ErrorNotification
        }
    }
</script>

