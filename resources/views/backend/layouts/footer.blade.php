<footer class="main-footer footer-modern">
    <div class="footer-modern__inner">
        <span class="footer-modern__copy">
            © {{ date('Y') }}
            <strong>{{ readConfig('site_name') }}</strong>
            <span class="footer-modern__sep">·</span>
            All rights reserved
        </span>
        <span class="footer-modern__brand">
            <span class="footer-modern__brand-icon" aria-hidden="true">CC</span>
            Powered by <strong>{{ readConfig('studio_name') }}</strong>
        </span>
    </div>
</footer>
