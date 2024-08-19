<button type="button" class="btn btn-success btn-sm back-button mb-2" onclick="goBack()">
    <i class="ti ti-arrow-left"></i>
    <span class="d-none d-md-inline">Kembali</span>
</button>
<script type="text/javascript">
    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/';
        }
    }
</script>
