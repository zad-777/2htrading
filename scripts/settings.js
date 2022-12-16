$(document).ready(function() {
    $(document).on('click', '.pending-btn', function() {
        var self = $(this);
        var account = JSON.parse(self.attr('data'));

        var pendingModal = $('.pending-modal');

        var id = pendingModal.find('[name=id]');
        var firstName = pendingModal.find('[name=first_name]');
        var middleName = pendingModal.find('[name=middle_name]');
        var lastName = pendingModal.find('[name=last_name]');
        var dateOfBirth = pendingModal.find('[name=date_of_birth]');
        var age = pendingModal.find('[name=age]');
        var gender = pendingModal.find('[name=gender]');
        var personType = pendingModal.find('[name=person_type]');
        var contactNumber = pendingModal.find('[name=contact_number]');
        var emailAddress = pendingModal.find('[name=email_address]');
        var homeAddress = pendingModal.find('[name=home_address]');
        var front = pendingModal.find('.photo1');
        var back = pendingModal.find('.photo2');

        id.val(account.id);
        firstName.val(account.first_name);
        middleName.val(account.middle_name);
        lastName.val(account.last_name);
        dateOfBirth.val(account.date_of_birth);
        age.val(account.age);
        gender.val(account.gender);
        personType.val(account.type);
        contactNumber.val(account.contact_number);
        emailAddress.val(account.email_address);
        homeAddress.val(account.home_address);
        front.attr('src', account.image1_location);
        back.attr('src', account.image2_location);

    });
});