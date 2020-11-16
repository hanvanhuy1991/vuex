<template>
<section class="content">
   <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

          <p>
            {{ $t('error.not_find_the_page') }}
            {{ $t('error.mean_while') }} <a href="/dashboard"> {{ $t('error.return_to_dashboard') }}</a>
            {{ $t('error.or') }}
            <a href="#" @click="backLink()"> {{ $t('error.back_to_link') }} </a>
          </p>
        </div>
        <!-- /.error-content -->
      </div>
</section>
</template>

<script>

export default {
    name: 'NotFound',

    created() {
        this.$store.commit('SET_ACTIVE_ASIDE', {
            aside: 'error'
        });
    },

    methods: {
        backLink() {
            this.$router.back();
        }
    }
};
</script>

<style lang="css">
</style>
