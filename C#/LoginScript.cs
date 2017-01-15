using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;


public class LoginScript : MonoBehaviour {

	public InputField username;
	public InputField pass;
	private string uri = "URL to - loginsecure.php";
	public GameObject forms;

	public void LoginAc(){
		username.text = username.text;
		pass.text = pass.text;
		string us = username.text;
		string pw = pass.text;
		StartCoroutine(SubmitLogin (us, pw));
	}

	IEnumerator SubmitLogin(string us, string pw){
		WWWForm form = new WWWForm();
		form.AddField ("usernamePost",us);
		form.AddField ("passwordPost",pw);

		WWW www = new WWW (uri, form);
		yield return www; 
		Debug.Log (www.text);

		if (www.text.Length > 0 && www.text.Length < 2) {
			// Success
			forms.SetActive(false);
		} else if(www.text.Length > 1) {
			Debug.LogError ("Problem.....");
		}

	}


}
