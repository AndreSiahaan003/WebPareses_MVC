/** @format */

document.addEventListener("DOMContentLoaded", function () {
  // I. LOGIKA FORM REGISTRASI DINAMIS (4 ATURAN UNSUR)

  const formRegistrasi = document.getElementById("formRegistrasi");

  if (formRegistrasi) {
    const unsurSelect = document.getElementById("unsur");

    const groupLembaga = document.getElementById("daerahLembagaGroup_Lembaga");
    const inputLembaga = document.getElementById("daerah_lembaga_lembaga");

    const groupDepartemen = document.getElementById(
      "daerahLembagaGroup_Departemen"
    );
    const inputDepartemen = document.getElementById(
      "daerah_lembaga_departemen"
    );

    const groupText = document.getElementById("daerahLembagaGroup_Text");
    const inputText = document.getElementById("daerah_lembaga_text");

    const groupResort = document.getElementById("resortGroup");
    const inputResort = document.getElementById("resort");

    const groupTanpaResort = [
      "Guru Jemaat",
      "Penatua",
      "Lembaga PA",
      "Lembaga PPR",
      "Lembaga Naposo Bulung",
    ];

    function updateFormRules() {
      const val = unsurSelect.value.trim();

      // 1. RESET SEMUA (Sembunyikan & Matikan)
      [groupLembaga, groupDepartemen, groupText, groupResort].forEach((el) => {
        if (el) el.style.display = "none";
      });
      [inputLembaga, inputDepartemen, inputText, inputResort].forEach((el) => {
        if (el) {
          el.disabled = true;
          el.required = false;
        }
      });

      // 2. TERAPKAN LOGIKA BARU

      // A. KETUA UMUM (Pakai Dropdown Lembaga)
      if (val === "Ketua Umum Lembaga Tingkat Pusat") {
        if (groupLembaga) {
          groupLembaga.style.display = "block";
          inputLembaga.disabled = false;
          inputLembaga.required = true;
        }
      }

      // B. BADAN USAHA (PERBAIKAN DI SINI)
      // Teks ini sekarang SAMA PERSIS dengan HTML (Huruf Besar Kecil)
      else if (
        val === "UNSUR BADAN USAHA LEMBAGA YANG DITUNJUK PUCUK PIMPINAN"
      ) {
        if (groupDepartemen) {
          groupDepartemen.style.display = "block";
          inputDepartemen.disabled = false;
          inputDepartemen.required = true;
        }
        // Resort tetap hidden
      }

      // C. PENDETA / ANGGOTA JEMAAT (Pakai Teks Manual + Resort)
      else if (val === "Pendeta" || val === "Anggota Jemaat") {
        if (groupText) {
          groupText.style.display = "block";
          inputText.disabled = false;
          inputText.required = true;
        }
        if (groupResort) {
          groupResort.style.display = "block";
          inputResort.disabled = false;
          // Resort opsional
        }
      }

      // D. SINTUA / LAINNYA (Pakai Teks Manual, TANPA Resort)
      else if (groupTanpaResort.includes(val)) {
        if (groupText) {
          groupText.style.display = "block";
          inputText.disabled = false;
          inputText.required = true;
        }
      }
    }

    unsurSelect.addEventListener("change", updateFormRules);
    updateFormRules();
  }

  // II. VALIDASI FORM PEMILIHAN (DENGAN PENGHITUNG JUMLAH)

  const formPemilihan = document.getElementById("formPemilihan");
  const alertBox = document.getElementById("validationAlert");
  const errorList = document.getElementById("validationList");

  if (formPemilihan) {
    formPemilihan.addEventListener("submit", function (e) {
      // 1. Hitung jumlah yang dicentang
      const countPareses = document.querySelectorAll(
        'input[name="pareses[]"]:checked'
      ).length;
      const countMajelis = document.querySelectorAll(
        'input[name="majelis[]"]:checked'
      ).length;
      const countBpk = document.querySelectorAll(
        'input[name="bpk[]"]:checked'
      ).length;

      let errors = [];

      // 2. Cek Aturan & Buat Pesan Error Spesifik
      if (countPareses < 1) {
        errors.push(
          `<strong>Pareses:</strong> Belum ada yang dipilih. (Minimal 1)`
        );
      } else if (countPareses > 16) {
        errors.push(
          `<strong>Pareses:</strong> Maksimal 16 calon. (Anda memilih <strong>${countPareses}</strong>)`
        );
      }

      if (countMajelis !== 15) {
        errors.push(
          `<strong>Majelis Pusat:</strong> Harus TEPAT 15 calon. (Anda memilih <strong>${countMajelis}</strong>)`
        );
      }

      if (countBpk !== 3) {
        errors.push(
          `<strong>BPK:</strong> Harus TEPAT 3 calon. (Anda memilih <strong>${countBpk}</strong>)`
        );
      }

      // 3. Tampilkan Error (Jika Ada)
      if (errors.length > 0) {
        e.preventDefault();

        if (errorList && alertBox) {
          errorList.innerHTML = "";
          errors.forEach(function (msg) {
            const li = document.createElement("li");
            li.innerHTML = msg;
            errorList.appendChild(li);
          });
          alertBox.classList.remove("d-none");
          alertBox.scrollIntoView({ behavior: "smooth", block: "center" });
        } else {
          alert(
            "Mohon lengkapi pilihan Anda:\n" +
              errors.join("\n").replace(/<[^>]*>?/gm, "")
          );
        }
        return false;
      } else {
        if (alertBox) alertBox.classList.add("d-none");
      }
    });
  }

  // III. PASSWORD TOGGLE
  const togglePasswordBtn = document.getElementById("togglePasswordBtn");
  const passwordInput = document.getElementById("passwordInput");
  const passwordIcon = document.getElementById("togglePasswordIcon");

  if (togglePasswordBtn && passwordInput && passwordIcon) {
    togglePasswordBtn.addEventListener("click", function () {
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      if (type === "password") {
        passwordIcon.classList.remove("bi-eye");
        passwordIcon.classList.add("bi-eye-slash");
      } else {
        passwordIcon.classList.remove("bi-eye-slash");
        passwordIcon.classList.add("bi-eye");
      }
    });
  }
});
