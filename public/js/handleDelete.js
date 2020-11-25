function Delete(selector, route) {
  document.querySelectorAll(selector).forEach(function (element) {
    element.addEventListener("click", function (e) {
      e.preventDefault();

      const id = this.getAttribute("data-id");

      fetch(`/delete-${route}/${id}`, {
        method: "DELETE",
      }).then(() => {
        const tr = this.parentNode.parentNode;

        tr.parentNode.removeChild(tr);
      });
    });
  });
}
