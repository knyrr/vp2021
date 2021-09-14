#Täisnurkse kolmnurga kohta on teada tema kaatetite pikkused.
#Leida kolmnurga pindala.
import math
a = int(input("Esimene kaatet "))
b = int(input("Teine kaatet "))
S = a * b / 2
c = math.sqrt(a**2 + b**2)
P = a + b + c
print("Kolmnurga pindala on", S, "ja ümbermõõt on", round(P,2))