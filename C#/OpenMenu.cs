using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class OpenMenu : MonoBehaviour {

	public GameObject target;
	public GameObject menuToHide;

	public void Open(){
		target.SetActive (true);
		gameObject.SetActive (false);
	}
}
