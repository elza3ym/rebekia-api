    <!--footer-->
    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
            <small>Copyright &copy; Re-Bekia {{ date('Y') }}</small>
            </div>
        </div>
    </footer>
    <!--/footer-->
    </div>
    <!--/main content wrapper-->
    <!--basic scripts-->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="/assets/vendor/popper.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/vendor/jquery-dropdown-master/jquery.dropdown.js"></script>
    <script src="/assets/vendor/m-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/assets/vendor/icheck/skins/icheck.min.js"></script>
    @yield('scripts')
    <!--[if lt IE 9]>
    <script src="/assets/vendor/modernizr.js"></script>
    <![endif]-->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "88eb4725-b88b-4979-8c7c-612431dae541",
            });
        });
    </script>
    <!--basic scripts initialization-->
    <script src="/assets/js/scripts.js"></script>
</body>
</html>

