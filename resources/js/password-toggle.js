document.addEventListener("click", function (e) {
    const btn = e.target.closest(".password-toggle");
    if (!btn) return;

    const input = btn.parentElement.querySelector("input");
    const show = input.type === "password";

    input.type = show ? "text" : "password";
    btn.setAttribute(
        "aria-label",
        show ? "パスワードを隠す" : "パスワードを表示",
    );
    btn.setAttribute("aria-pressed", String(show));
    btn.querySelector(".eye-open").classList.toggle("hidden", show);
    btn.querySelector(".eye-closed").classList.toggle("hidden", !show);
});
