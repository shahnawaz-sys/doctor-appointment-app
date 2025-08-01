let currentView = 'dashboard';

        function showView(viewName) {

            document.querySelectorAll('.main-content .content-card').forEach(card => {
                card.classList.add('hidden');
            });

            document.querySelectorAll('.nav-buttons .btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-secondary');
            });


            const viewToShow = document.getElementById(viewName);
            if (viewToShow) {
                viewToShow.classList.remove('hidden');
            }

            currentView = viewName;


            const activeBtn = document.querySelector(`.nav-buttons .btn[onclick="showView('${viewName}')"]`);
            if (activeBtn) {
                activeBtn.classList.add('active', 'btn-primary');
                activeBtn.classList.remove('btn-secondary');
            }
        }


        document.getElementById('appointment-date').min = new Date().toISOString().split('T')[0];

        
        document.addEventListener('DOMContentLoaded', function() {
           
            showView('dashboard');
        });



        
        // ---------- alert badge js code
   
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 3000); // 3 seconds


    //  --------alert badge js code