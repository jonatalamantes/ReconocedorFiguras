from svmutil import *

m = svm_load_model('./svm_figuras.model');

archivo = '../db/Tran/10'
file = open(archivo, "r")
instancia = []
while True:
    letra = file.read(1)
    if not letra:
        break
    elif letra == '\n':
        continue

    instancia.append(int(letra))

print "Leida instancia"
p_label, p_acc, p_val = svm_predict([3], [instancia], m)

print p_label
print p_acc
print p_val