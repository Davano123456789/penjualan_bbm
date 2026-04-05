    <!-- Sidebar Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        
        // Function to toggle sidebar on small screens
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            if (window.innerWidth >= 768) {
                mainContent.classList.toggle('ml-0');
            }
        }
    </script>
    
    <!-- Flasher Script Initialization -->
    <?php Flasher::flash(); ?>

</body>
</html>
