	</div> <!-- / Main-layout -->
        <script src="<?php echo base_url(); ?>js/vendors.js"></script>      
        <script src="<?php echo base_url(); ?>js/plugins.js"></script>        
        <script src="<?php echo base_url(); ?>js/main.js"></script>
        
        <?php if ($this->uri->segment(1) == "erd"): ?>    
        <script src="<?php echo base_url(); ?>js/erd.js"></script>
        <?php endif; ?>  
          
    </body>
</html>