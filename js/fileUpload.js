import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm'

const supabaseUrl = 'https://aenlcrtjqgnxwzynorto.supabase.co'
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFlbmxjcnRqcWdueHd6eW5vcnRvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE5NTcyOTEsImV4cCI6MjA1NzUzMzI5MX0.cIFQtbPfoXvagGfW9fdg4qV_-UxvLB9luLhXqt1aFVs';
const supabase = createClient(supabaseUrl, supabaseKey)

document.getElementById("importButton").addEventListener("click", function() {
    document.getElementById("fileUpload").click();
});

const preview = document.querySelector('.preview');
var selectedFile;
document
  .getElementById("fileUpload")
  .addEventListener("change", function(event) {
    selectedFile = event.target.files[0];
    const fileType = selectedFile.type;
    preview.style.display = 'block';
    preview.textContent = `Archivo cargado: ${selectedFile.name}`;
    if (fileType !== "application/vnd.ms-excel" && fileType !== "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
        alert("Solo se permiten archivos excel (.xls o .xlsx)");
        event.target.value = "";
        return
    }
  });
document
  .getElementById("uploadExcel")
  .addEventListener("click", function() {
    if (selectedFile) {
      var fileReader = new FileReader();
      fileReader.onload = function(event) {
        var data = event.target.result;

        var workbook = XLSX.read(data, {
          type: "binary"
        });
        workbook.SheetNames.forEach(async (sheet) => {
          let rowObject = XLSX.utils.sheet_to_row_object_array(
            workbook.Sheets[sheet]
          );
          let jsonObject = JSON.stringify(rowObject, null, 2);

          const { data, error } = await supabase
            .from('medidata')
            .select()
            if (data.length > 0) {
                const { error } = await supabase
                .from('medidata')
                .update({ data: jsonObject })
                .eq('id', 1)
                if (error) return alert('Ha ocurrido un error al cargar la data.')
                alert('Datos cargados correctamente. Ahora solo debe hacer las consultas en: https://medipasscl.vercel.app/')
                location.href = 'https://medipasscl.vercel.app/';
                preview.style.display = 'block';
                return;
            }
            try {
                const { error } = await supabase
                .from('medidata')
                .insert({ data: jsonObject })
                if (error) return alert('Ha ocurrido un error al cargar la data.')
                alert('Datos cargados correctamente. Ahora solo debe hacer las consultas en: https://medipasscl.vercel.app/')
                preview.style.display = 'block';
                location.href = 'https://medipasscl.vercel.app/';
            } catch (error) {}
        });
      };
      fileReader.readAsBinaryString(selectedFile);
    } else {
        alert('Se requiere importar el archivo excel para continuar.')
    }
});