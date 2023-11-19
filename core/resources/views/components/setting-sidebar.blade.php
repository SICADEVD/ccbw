<!-- SETTINGS SIDEBAR START -->
<div class="mobile-close-overlay w-100 h-100" id="close-settings-overlay"></div>
<div class="settings-sidebar bg-white py-3" id="mob-settings-sidebar">
    <a class="d-block d-lg-none close-it" id="close-settings"><i class="fa fa-times"></i></a>

    <!-- SETTINGS SEARCH START -->
    <form class="border-bottom-grey px-4 pb-3 d-flex">
        <div class="input-group rounded py-1 border-grey">
            <div class="input-group-prepend">
                <span class="input-group-text border-0 bg-white">
                    <i class="fa fa-search f-12 text-lightest"></i>
                </span>
            </div>
            <input type="text" id="search-setting-menu" class="form-control border-0 f-12 pl-0"
                   placeholder="@lang('app.search')">
        </div>
    </form>
    <!-- SETTINGS SEARCH END -->

    <!-- SETTINGS MENU START -->
    <ul class="settings-menu" id="settingsMenu">
    <x-setting-menu-item :active="$activeMenu" menu="cooperative_settings" :href="route('manager.settings.cooperative-settings.index')" :text="__('Paramètres de Coopérative')"/>
    <x-setting-menu-item :active="$activeMenu" menu="durabilite_settings" :href="route('manager.settings.durabilite-settings.index')" :text="__('Paramètre Programme de Durabilité')"/>
    <x-setting-menu-item :active="$activeMenu" menu="section_settings" :href="route('manager.settings.section-settings.index')" :text="__('Paramètre de Section')"/>
    <x-setting-menu-item :active="$activeMenu" menu="localite_settings" :href="route('manager.settings.localite-settings.index')" :text="__('Paramètre de Localite')"/>
    <x-setting-menu-item :active="$activeMenu" menu="attendance_settings" :href="route('manager.settings.attendance-settings.index')" :text="__('app.menu.attendanceSettings')"/>
    <x-setting-menu-item :active="$activeMenu" menu="leave_settings" :href="route('manager.settings.leaves-settings.index')" :text="__('app.menu.leaveSettings')"/>
    <x-setting-menu-item :active="$activeMenu" menu="campagne_settings" :href="route('manager.settings.campagne.index')" :text="__('Paramètre Campagne')"/>
    <x-setting-menu-item :active="$activeMenu" menu="travauxDangereux_settings" :href="route('manager.settings.travauxDangereux.index')" :text="__('Paramètre Travaux Dangereux')"/>
    <x-setting-menu-item :active="$activeMenu" menu="travauxLegers_settings" :href="route('manager.settings.travauxLegers.index')" :text="__('Paramètre Travaux Legers')"/>
    <x-setting-menu-item :active="$activeMenu" menu="arretEcole_settings" :href="route('manager.settings.arretEcole.index')" :text="__('Paramètre Arrets Ecole')"/>
    <x-setting-menu-item :active="$activeMenu" menu="typeFormation_settings" :href="route('manager.settings.typeFormation.index')" :text="__('Paramètre Types de Formation')"/>
    <x-setting-menu-item :active="$activeMenu" menu="themeFormation_settings" :href="route('manager.settings.themeFormation.index')" :text="__('Paramètre Themes de Formation')"/>
    <x-setting-menu-item :active="$activeMenu" menu="moduleFormationStaff_settings" :href="route('manager.settings.moduleFormationStaff.index')" :text="__('Paramètre Modules de Formation Staffs')"/>
    <x-setting-menu-item :active="$activeMenu" menu="themeFormationStaff_settings" :href="route('manager.settings.themeFormationStaff.index')" :text="__('Paramètre Themes de Formation Staffs')"/>
    <x-setting-menu-item :active="$activeMenu" menu="categorieQuestionnaire_settings" :href="route('manager.settings.categorieQuestionnaire.index')" :text="__('Paramètre Categorie Questionnaire')"/>
    <x-setting-menu-item :active="$activeMenu" menu="questionnaire_settings" :href="route('manager.settings.questionnaire.index')" :text="__('Paramètre Questionnaire')"/>
    <x-setting-menu-item :active="$activeMenu" menu="especeArbre_settings" :href="route('manager.settings.especeArbre.index')" :text="__('Paramètre Espèces Arbres')"/>
    <x-setting-menu-item :active="$activeMenu" menu="typeArchive_settings" :href="route('manager.settings.typeArchive.index')" :text="__('Paramètre Type Archives')"/> 

    </ul>
    <!-- SETTINGS MENU END -->

</div>
<!-- SETTINGS SIDEBAR END -->

<script>
    $("body").on("click", ".ajax-tab", function (event) {
        event.preventDefault();

        $('.project-menu .p-sub-menu').removeClass('active');
        $(this).addClass('active');

        const requestUrl = this.href;
       
        $.easyAjax({
            url: requestUrl,
            blockUI: true,
            container: ".content-wrapper",
            historyPush: true,
            success: function (response) {
                if (response.status === "success") {
                    $('.content-wrapper').html(response.html);
                    init('.content-wrapper');
                }
            }
        });
    });

    $("#search-setting-menu").on("keyup", function () {
        var value = this.value.toLowerCase().trim();
        $("#settingsMenu li").show().filter(function () {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });

    document.querySelector('#settingsMenu .active').scrollIntoView()

</script>
