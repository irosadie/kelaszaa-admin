<?= $this->render('@app/views/components/_headers/index') ?>
<main>
    <section class="tw-h-auto tw-bg-white tw-rounded-t-[32px] -tw-mt-12 tw-bg-sweeter tw-bg-no-repeat tw-bg-left-top">
        <?= $this->render('@app/views/components/_sections/intro', 
        [
            'data'=>(object)$data
        ]) ?>
    </section>
    <section class="tw-h-auto tw-bg-[#f8f8f8] tw-rounded-t-[32px] -tw-mt-12">
        <?= $this->render('@app/views/components/_sections/articles', 
        [
            'title'=> $data->fe_settings_article_name_txt,
            'data'=>(object)$data,
            'pager'=> (boolean)$data->fe_settings_article_pagination_bool, 
            'dataProvider'=> $article['dataProvider'],
            'searchModel' => $article['searchModel']
        ]) ?>
    </section>
    <section
        class="tw-h-auto tw-bg-white tw-rounded-t-[32px] -tw-mt-12 tw-bg-sweeter2 tw-bg-no-repeat tw-bg-right-bottom">
        <?= $this->render('@app/views/components/_sections/features', 
        [
            'data'=>(object)$data
        ]) ?>
    </section>
    <section class="tw-h-auto tw-bg-[#f8f8f8] -tw-mt-12 tw-relative wave-after tablet:tw-pb-20 tw-text-[#142b72]">
        <?= $this->render('@app/views/components/_sections/quotes', 
        [
            'data'=>(object)$data
        ]) ?>
    </section>
    <section class=" tw-h-auto tw-bg-white tw-text-[#142b72]">
        <?= $this->render('@app/views/components/_sections/faqs', 
        [
            'data'=>(object)$data
        ]) ?>
    </section>
</main>