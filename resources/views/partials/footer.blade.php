<footer class="main-footer hidden-print">
    <div class="pull-right hidden-xs">
        <b>Version</b> {{ \App\Models\Setting::where('setting_key','system_version')->first()->setting_value }}
    </div>
    <strong>Copyright &copy; {{ date("Y") }} <a
                href="{{ \App\Models\Setting::where('setting_key','company_website')->first()->setting_value }}">{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}</a>.</strong>
    All rights
    reserved.
</footer>