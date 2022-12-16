$(document).ready(function() {
    $('.th-alert').each(function() {
        var self = $(this);
        var closeButton = self.find('.th-alert-close');
        closeButton.on('click', function() {
            self.remove();
        });
    });

    $('.th-info').each(function() {
        var self = $(this);
        var closeButton = self.find('.th-info-close');
        closeButton.on('click', function() {
            self.remove();
        });
    });

    var searchbar = $('.searchbar');

    if(searchbar.length > 0) {
        var searchResult = $('.searchbar-result-list');
        searchbar.on('input', function() {
            searchResult.removeClass('hidden');
        });
        searchbar.on('focusout', function() {
            setTimeout(function() {
                searchResult.addClass('hidden');
            }, 500);
        });
    }

    var parents = '.th-button, .th-nav-toggler, .th-nav-menu-item';

    $(document).on('click', parents, function(e) {
        
        parent = $(this);

        if(!parent.hasClass('disabled')) {

            var diameter = Math.max(parent.outerWidth(), parent.outerHeight());
            var radius = diameter / 2;

            var x = e.clientX - (parent.offset().left + radius);
            var y = e.clientY - ((parent.offset().top - $(document).scrollTop()) + radius);
        
            var ripple = document.createElement('span');
            ripple.setAttribute('class', 'ripple');
            ripple.style.width = `${diameter}px`;
            ripple.style.height = `${diameter}px`;
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
        
            parent.append(ripple);
            
            setTimeout(function() {
                ripple.remove();
            }, 1000);

        }

    });

    $('.th-modal').each(function() {
        var modal = $(this);
        closeButton = modal.find('.th-modal-content-close, .th-modal-content-footer-close');
        closeButton.on('click', function() {
            modal.addClass('hidden');
            modal.removeClass('shown');
            $('body').css('overflow', 'auto');
        });
    });

    $(document).on('click', '.th-modal-toggler', function() {
        var self = $(this);
        var modal = $("#" + self.attr('open-modal'));
        if(modal.length > 0) {
            modal.removeClass('hidden');
            modal.addClass('shown');
            $('body').css('overflow', 'hidden');
        }
    });

    $(document).on('click', '.th-dropdown-toggler', function() {
        var self = $(this);
        var dropdown = $("#" + self.attr('open-dropdown'));
        if(dropdown.length > 0) {
            dropdown.toggleClass('shown');
        }
    });

    var passwordWrappers = $('.th-password-wrapper');

    passwordWrappers.each(function() {

        var passwordWrapper = $(this);
        var passwordInput = passwordWrapper.find('[type=password]');

        if(passwordInput.length > 0) {

            var showPassword = false;
            var passwordToggler = document.createElement('a');
            passwordToggler.setAttribute('class', 'th-password-toggler');
            passwordToggler.innerHTML = '<i class="far fa-eye"></i>';
            passwordInput.before(passwordToggler);

            var passwordToggler = passwordWrapper.find('.th-password-toggler');

            passwordToggler.on('click', function() {
                if(!showPassword) {
                    passwordInput.attr('type', 'text');
                    passwordToggler.html('<i class="far fa-eye-slash"></i>');
                    showPassword = true;
                } else {
                    passwordInput.attr('type', 'password');
                    passwordToggler.html('<i class="far fa-eye"></i>');
                    showPassword = false;
                }
            });

        }

    });

    $('.th-photo-viewer').each(function() {
        var self = $(this);
        var attr = self.attr('open-modal');
        self.on('click', function() {
            var modalID = '#' + attr;
            var modalViewer = $(modalID);
            if(modalViewer.length > 0)
                $('body').css('overflow', 'hidden');
                modalViewer.addClass('shown'); 
        });
    });

    $(window).on('keyup', function(e) {
        if(e.keyCode == 27) {
            var modals = $('.th-modal');
            modals.each(function() {
                var self = $(this);
                if(self.hasClass('shown')) {
                    self.removeClass('shown');
                    $('body').css('overflow', 'auto');
                }
            });
            var modalPhotos = $('.th-photo-viewer-modal');
            modalPhotos.each(function() {
                var self = $(this);
                if(self.hasClass('shown')) {
                    self.removeClass('shown');
                    $('body').css('overflow', 'auto');
                }
            });
        }
    });

    $('.th-upload').each(function() {
        var upload = $(this);
        var input = upload.find('[type=file]');
        var width = Math.round(upload.innerWidth());
        //upload.css('height', width + 'px');
        /*upload.on('resize', function() {
            upload.css('height', width + 'px');
        });*/
        var remove = upload.find('.remove-button');
        var container = upload.find('.image-container');
        input.on('change', function(e) {
            container.attr('src', URL.createObjectURL(e.target.files[0]));
            container.onload = function() {
                URL.revokeObjectURL(output.src);
            }
            upload.addClass('image-shown');
        });
        remove.on('click', function() {
            container.attr('src', '');
            input.prop('value', '');
            upload.removeClass('image-shown');
        });
    });

    $(document).on('ready', '.th-input-checkbox [type=checkbox]', function() {
        var self = $(this);
        var parent = self.parent();
        if(self.prop('checked')) {
            parent.addClass('checked');
        } else {
            parent.removeClass('checked');
        }
    }); 

    $(document).on('change', '.th-input-checkbox [type=checkbox]', function() {
        var self = $(this);
        var parent = self.parent();
        if(self.prop('checked')) {
            parent.addClass('checked');
        } else {
            parent.removeClass('checked');
        }
    }); 

    $('[type=submit], th-submit-button').each(function() {
        var self = $(this);
        self.on('click', function() {
            self.addClass('th-disabled');
            self.val("Please wait...");
        });
    });

});