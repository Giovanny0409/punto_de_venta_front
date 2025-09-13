# punto_de_venta_linux

🧭 Flujo de trabajo con ramas Git: main, dev y vat
Este proyecto utiliza un flujo de trabajo basado en ramas para mantener el código organizado y facilitar la colaboración. A continuación se describen las instrucciones para trabajar correctamente con las ramas main, dev y vat.
🌿 Ramas principales
|  |  | 
| main :Rama estable. Contiene el código listo para producción.
| dev  :Rama de desarrollo. Aquí se integran nuevas funcionalidades antes de pasar a main.
| vat :Rama experimental. Se usa para pruebas, prototipos o ideas en desarrollo.



🛠️ Cómo trabajar con las ramas
1. Clona el repositorio
git clone
cd repositorio


2. Cambia a la rama de desarrollo
git checkout dev


3. Crea una nueva rama para tu tarea
git checkout -b (siempre iniciar con la inicial de cada rama princila)nombre-de-tu-rama
DEV_inicio_sesion
VAT_prueba_tiket

4. Realiza tus cambios y haz commits descriptivos
git add .
git commit -m "Agrega formulario de login con validación"


💡 Usa mensajes de commit claros y concisos. Evita mensajes genéricos como "update" o "fix".

5. Sube tu rama al repositorio remoto
git push origin nombre-de-tu-rama



🔁 Cómo hacer un Pull Request (PR)
- Ve a GitHub y abre un Pull Request desde tu rama hacia dev.
- Agrega una descripción clara de los cambios realizados.
- Solicita revisión si es necesario.
- Una vez aprobado, se hace merge a dev.
✅ Los cambios en main solo se hacen desde dev mediante un Pull Request aprobado.


🧪 Rama vat: uso experimental
- Puedes usar vat para probar ideas sin afectar el flujo principal.
- Se recomienda hacer PR desde vat hacia dev si alguna prueba resulta útil.

📌 Buenas prácticas
- Mantén tus ramas actualizadas con dev usando git pull.
- Borra ramas locales y remotas que ya fueron fusionadas.
- Usa etiquetas como feature/, fix/, hotfix/ para nombrar tus ramas.
