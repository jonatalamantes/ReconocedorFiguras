from svmutil import *

#Lectura del modelo
m = svm_load_model('python-svm/svm_figuras.model');

#Lectura del archivo con los datos
archivo = 'python-svm/input.dat'
file = open(archivo, "r")
instancia = []
while True:
    letra = file.read(1)
    if not letra:
        break
    elif letra == '\n':
        continue

    instancia.append(int(letra))

#Prediccion del datos
p_label, p_acc, p_val = svm_predict([0], [instancia], m)

print p_label[0]
print p_acc
print p_val

#Guardar el datos en un archivo
exit(int(p_label[0]))
