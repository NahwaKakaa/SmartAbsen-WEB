document.addEventListener("DOMContentLoaded", function () {
    if (typeof bootstrap === "undefined") {
        console.error(
            "Bootstrap is not loaded. Modal functionality might be affected."
        );
        return;
    }

    const confirmationModalElement =
        document.getElementById("confirmationModal");
    if (!confirmationModalElement) {
        return;
    }

    const confirmationModal = new bootstrap.Modal(confirmationModalElement);
    const confirmationMessage = document.getElementById("confirmationMessage");
    const confirmActionButton = document.getElementById("confirmActionButton");
    let currentForm = null;

    document.querySelectorAll("form.d-inline").forEach((form) => {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            currentForm = this;
            const message =
                this.getAttribute("data-confirm-message") ||
                "Apakah Anda yakin ingin melakukan aksi ini?";
            if (confirmationMessage) {
                confirmationMessage.textContent = message;
            }
            confirmationModal.show();
        });
    });

    if (confirmActionButton) {
        confirmActionButton.addEventListener("click", function () {
            if (currentForm) {
                confirmationModal.hide();
                currentForm.submit(); //
            }
        });
    }

    const updateModalColors = () => {
        const modalContent = document.querySelector(
            "#confirmationModal .modal-content"
        );
        const modalHeader = document.querySelector(
            "#confirmationModal .modal-header"
        );
        const modalTitle = document.querySelector(
            "#confirmationModal .modal-title"
        );
        const modalBody = document.querySelector(
            "#confirmationModal .modal-body"
        );
        const modalCloseBtn = document.querySelector(
            "#confirmationModal .btn-close"
        );

        if (
            !modalContent ||
            !modalHeader ||
            !modalTitle ||
            !modalBody ||
            !modalCloseBtn
        ) {
            return;
        }

        if (document.body.classList.contains("dark-mode")) {
            modalContent.style.backgroundColor = "var(--modal-bg)";
            modalContent.style.borderColor = "var(--card-border)";
            modalHeader.style.backgroundColor = "var(--modal-header-bg)";
            modalHeader.classList.remove("bg-primary", "text-white");
            modalHeader.style.color = "var(--text-color)";
            modalTitle.style.color = "var(--text-color)";
            modalBody.style.color = "var(--text-color)";
            modalCloseBtn.classList.add("btn-close-white");
            modalCloseBtn.classList.remove("btn-close-dark");
        } else {
            modalContent.style.backgroundColor = "var(--modal-bg)";
            modalContent.style.borderColor = "var(--card-border)";
            modalHeader.style.backgroundColor = "";
            modalHeader.classList.add("bg-primary", "text-white");
            modalHeader.style.color = "";
            modalTitle.style.color = "";
            modalBody.style.color = "";
            modalCloseBtn.classList.remove("btn-close-white");
        }
    };

    updateModalColors();

    if (document.body && typeof MutationObserver !== "undefined") {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === "class") {
                    updateModalColors();
                }
            });
        });
        observer.observe(document.body, { attributes: true });
    }
});
